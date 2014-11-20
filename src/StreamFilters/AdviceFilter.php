<?php

/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage StreamFilters
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\StreamFilters;

use AppserverIo\Doppelgaenger\Dictionaries\ReservedKeywords;
use AppserverIo\Doppelgaenger\Entities\Annotations\Joinpoints\Around;
use AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition;
use AppserverIo\Doppelgaenger\Dictionaries\Placeholders;

/**
 * AppserverIo\Doppelgaenger\StreamFilters\PostconditionFilter
 *
 * This filter will buffer the input stream and add all postcondition related information at prepared locations
 * (see $dependencies)
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage StreamFilters
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class AdviceFilter extends AbstractFilter
{
    /**
     * Order number if filters are used as a stack, higher means below others
     *
     * @const integer FILTER_ORDER
     */
    const FILTER_ORDER = 2;

    /**
     * @var  $aspectRegister
     */
    protected $aspectRegister;

    /**
     * Other filters on which we depend
     *
     * @var array $dependencies
     */
    protected $dependencies = array('SkeletonFilter');

    /**
     * The main filter method.
     * Implemented according to \php_user_filter class. Will loop over all stream buckets, buffer them and perform
     * the needed actions.
     *
     * @param resource $in       Incoming bucket brigade we need to filter
     * @param resource $out      Outgoing bucket brigade with already filtered content
     * @param integer  $consumed The count of altered characters as buckets pass the filter
     * @param boolean  $closing  Is the stream about to close?
     *
     * @return integer
     *
     * @link http://www.php.net/manual/en/php-user-filter.filter.php
     */
    public function filter($in, $out, &$consumed, $closing)
    {
        // Get our buckets from the stream
        while ($bucket = stream_bucket_make_writeable($in)) {

            // Get the tokens
            $tokens = token_get_all($bucket->data);

            $functionDefinitions = $this->params['functionDefinitions'];
            $this->aspectRegister = $this->params['aspectRegister'];

            // Go through the tokens and check what we found
            $tokensCount = count($tokens);
            for ($i = 0; $i < $tokensCount; $i++) {

                // Did we find a function? If so check if we know that thing and insert the code of its preconditions.
                if (is_array($tokens[$i]) && $tokens[$i][0] === T_FUNCTION && is_array($tokens[$i + 2])) {

                    // Get the name of the function
                    $functionName = $tokens[$i + 2][1];

                    // Check if we got the function in our list, if not continue
                    $functionDefinition = $functionDefinitions->get($functionName);

                    if (!$functionDefinition instanceof FunctionDefinition) {

                        continue;

                    } else {

                        $stuff = $this->findMatchingAdvices($functionDefinition);
                        error_log($functionDefinition->getName() . ': ' . var_export($stuff, true));

                        // get the pointcuts which are associated with this function already and have a look at what we have to do
                        $sortedFunctionPointcuts = $this->sortPointcutExpressions($functionDefinition->getPointcutExpressions());
                        if (!empty($sortedFunctionPointcuts)) {

                            // before we weave in any advice code we have to make a MethodInvocation object ready
                            $this->injectInvocationCode($bucket->data, $functionDefinition);

                            // inject the advice code
                            $this->injectAdviceCode($bucket->data, $sortedFunctionPointcuts, $functionName);
                        }

                        // "Destroy" code and function definition
                        $functionDefinition = null;
                    }
                }
            }

            // Tell them how much we already processed, and stuff it back into the output
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }

        return PSFS_PASS_ON;
    }

    /**
     * Will inject the advice code for the different joinpoints based on sorted joinpoints
     *
     * @param string $bucketData                Reference on the current bucket's data
     * @param array  $sortedPointcutExpressions Array of pointcut expressions sorted by joinpoints
     * @param string $functionName              Name of the function to inject the advices into
     *
     * @return boolean
     */
    protected function injectAdviceCode(& $bucketData, array $sortedPointcutExpressions, $functionName)
    {
        // iterate over the sorted pointcuts and insert the code
        foreach ($sortedPointcutExpressions as $joinpoint => $pointcutExpressions) {

            // get placeholder and replacement prefix based on joinpoint
            $placeholderName = strtoupper($joinpoint) . '_JOINPOINT';
            $placeholderHook = constant('\AppserverIo\Doppelgaenger\Dictionaries\Placeholders::' . $placeholderName) .
                $functionName . Placeholders::PLACEHOLDER_CLOSE;

            $replacementPrefix = $placeholderHook;
            if ($joinpoint === Around::ANNOTATION) {
                $replacementPrefix = '';
            }

            foreach ($pointcutExpressions as $pointcutExpression) {

                // Insert the code
                $bucketData = str_replace(
                    $placeholderHook,
                    $replacementPrefix . $pointcutExpression->getString(),
                    $bucketData
                );
            }
        }

        return true;
    }

    /**
     * @param FunctionDefinition $functionDefinition
     * @return array
     */
    protected function findMatchingAdvices(FunctionDefinition $functionDefinition)
    {
        $matches = array();
        foreach ($this->aspectRegister as $aspect) {

            foreach ($aspect->advices as $advice) {

                foreach ($advice->pointcuts as $pointcut) {

                    if ($pointcut->matches($functionDefinition)) {

                        $matches[] = $advice;
                        break;
                    }
                }
            }
        }

        return $matches;
    }

    /**
     * Will inject invocation code for a given function into a given piece of code.
     * Invocation code will be the instantiation of a \AppserverIo\Doppelgaenger\Entities\MethodInvocation object
     * as a basic representation of the given function
     *
     * @param string             $bucketData         Reference on the current bucket's data
     * @param FunctionDefinition $functionDefinition Definition of the function to inject invocation code into
     *
     * @return boolean
     */
    protected function injectInvocationCode(& $bucketData, FunctionDefinition $functionDefinition)
    {

        // start building up the code
        $code = ReservedKeywords::METHOD_INVOCATION_OBJECT . ' = new \AppserverIo\Doppelgaenger\Entities\MethodInvocation(
            ';

        // we have to differentiate between static and object calls
        if ($functionDefinition->getIsStatic()) {

            $code .= 'array(__CLASS__, \'' . $functionDefinition->getName() . ReservedKeywords::ORIGINAL_FUNCTION_SUFFIX . '\'),
                __CLASS__,';

        } else {

            $code .= 'array($this, \'' . $functionDefinition->getName() . ReservedKeywords::ORIGINAL_FUNCTION_SUFFIX . '\'),
                $this,';
        }

        // continue with the access modifiers
        $code .= ($functionDefinition->getIsAbstract() ? 'true' : 'false') . ',
            ' . ($functionDefinition->getIsFinal() ? 'true' : 'false') . ',
            ' . ($functionDefinition->getIsStatic() ? 'true' : 'false') . ',
            ';

        // we have to build up manual parameter collection as func_get_args() only returns copies
        // @see http://php.net/manual/en/function.func-get-args.php
        $parametersCode = 'array(';
        foreach ($functionDefinition->getParameterDefinitions() as $parameterDefinition) {

            $name = $parameterDefinition->name;
            $parametersCode .= '\'' . substr($name, 1) . '\' => ' . $name . ',';
        }
        $parametersCode .= ')';

        $code .= '\'' . $functionDefinition->getName() . '\',
            ' .$parametersCode . ',
             __CLASS__,
            \'' . $functionDefinition->getVisibility() . '\'
            );';

        // Insert the code
        $placeholder = Placeholders::FUNCTION_BEGIN . $functionDefinition->getName() . Placeholders::PLACEHOLDER_CLOSE;
        $bucketData = str_replace(
            $placeholder,
            $placeholder . $code,
            $bucketData
        );

        return true;
    }

    /**
     * Will sort a list of given pointcut expressions based on the joinpoints associated with them
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Lists\PointcutExpressionList $pointcutExpressions List of pointcut
     *          expressions
     *
     * @return array
     */
    protected function sortPointcutExpressions($pointcutExpressions)
    {
        // sort by joinpoint code hooks
        $sortedPointcutExpressions = array();
        foreach ($pointcutExpressions as $pointcutExpression) {

            $sortedPointcutExpressions[$pointcutExpression->getJoinpoint()->codeHook][] = $pointcutExpression;
        }

        return $sortedPointcutExpressions;
    }
}
