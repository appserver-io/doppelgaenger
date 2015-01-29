<?php

/**
 * \AppserverIo\Doppelgaenger\StreamFilters\AdviceFilter
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\StreamFilters;

use AppserverIo\Doppelgaenger\Dictionaries\ReservedKeywords;
use AppserverIo\Doppelgaenger\Entities\Annotations\Joinpoints\Around;
use AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition;
use AppserverIo\Doppelgaenger\Dictionaries\Placeholders;
use AppserverIo\Doppelgaenger\Entities\Joinpoint;
use AppserverIo\Doppelgaenger\Entities\Lists\PointcutExpressionList;
use AppserverIo\Doppelgaenger\Entities\Pointcuts\AdvisePointcut;
use AppserverIo\Doppelgaenger\Entities\Pointcuts\AndPointcut;
use AppserverIo\Doppelgaenger\Entities\Pointcuts\PointcutPointcut;

/**
 * This filter will buffer the input stream and add all advice calls into their respective join-point locations
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
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
        // get our buckets from the stream
        while ($bucket = stream_bucket_make_writeable($in)) {
            // get the tokens
            $tokens = token_get_all($bucket->data);

            $functionDefinitions = $this->params['functionDefinitions'];
            $this->aspectRegister = $this->params['aspectRegister'];

            // go through the tokens and check what we found
            $tokensCount = count($tokens);
            for ($i = 0; $i < $tokensCount; $i++) {
                // did we find a function? If so check if we know that thing and insert the code of its preconditions.
                if (is_array($tokens[$i]) && $tokens[$i][0] === T_FUNCTION && is_array($tokens[$i + 2])) {
                    // get the name of the function
                    $functionName = $tokens[$i + 2][1];

                    // check if we got the function in our list, if not continue
                    $functionDefinition = $functionDefinitions->get($functionName);

                    if (!$functionDefinition instanceof FunctionDefinition) {
                        continue;

                    } else {
                        // collect all pointcut expressions, advice based as well as directly defined ones
                        $pointcutExpressions = $functionDefinition->getPointcutExpressions();
                        $pointcutExpressions->attach($this->findAdvicePointcutExpressions($functionDefinition));

                        if ($pointcutExpressions->count() > 0) {
                            // sort all relevant pointcut expressions by their joinpoint code hooks
                            $sortedFunctionPointcuts = $this->sortPointcutExpressions($pointcutExpressions);

                            // get all the callbacks for around advices to build a proper advice chain
                            $callbackChain = $this->generateAdviceCallbacks($sortedFunctionPointcuts, $functionDefinition);

                            // before we weave in any advice code we have to make a MethodInvocation object ready
                            $this->injectInvocationCode($bucket->data, $functionDefinition, $callbackChain);

                            // inject the advice code
                            $this->injectAdviceCode($bucket->data, $sortedFunctionPointcuts, $functionName);
                        }

                        // "destroy" function definition
                        $functionDefinition = null;
                    }
                }
            }

            // tell them how much we already processed, and stuff it back into the output
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
            // only do something if we got expressions
            if (empty($pointcutExpressions)) {
                continue;
            }

            // get placeholder and replacement prefix based on joinpoint
            $placeholderName = strtoupper($joinpoint) . '_JOINPOINT';
            $placeholderHook = constant('\AppserverIo\Doppelgaenger\Dictionaries\Placeholders::' . $placeholderName) .
                $functionName . Placeholders::PLACEHOLDER_CLOSE;

            // around advices have to be woven differently
            if ($joinpoint === Around::ANNOTATION && !$pointcutExpressions[0]->getPointcut() instanceof PointcutPointcut) {
                // insert the code but make sure to inject only the first one in the row, as the advice chain will
                // be implemented via the advice chain
                $pointcutExpression = $pointcutExpressions[0];
                $bucketData = str_replace(
                    $placeholderHook,
                    $pointcutExpression->toCode(),
                    $bucketData
                );

            } else {
                // iterate all the others and inject the code
                foreach ($pointcutExpressions as $pointcutExpression) {
                    $bucketData = str_replace(
                        $placeholderHook,
                        $placeholderHook . $pointcutExpression->toCode(),
                        $bucketData
                    );
                }
            }
        }

        return true;
    }

    /**
     * Will look at all advices known to the aspect register and filter out pointcut expressions matching the
     * function in question
     * Will return a list of pointcut expressions including distinctive pointcuts to weave in the associated advices
     *
     * @param FunctionDefinition $functionDefinition Definition of the function to match known advices against
     *
     * @return \AppserverIo\Doppelgaenger\Entities\Lists\PointcutExpressionList
     */
    protected function findAdvicePointcutExpressions(FunctionDefinition $functionDefinition)
    {
        // we have to search for all advices, all of their pointcuts and all pointcut expressions those reference
        $pointcutExpressions = new PointcutExpressionList();
        foreach ($this->aspectRegister as $aspect) {
            foreach ($aspect->getAdvices() as $advice) {
                foreach ($advice->getPointcuts() as $pointcut) {
                    // there should be no other pointcuts than those referencing pointcut definitions
                    if (!$pointcut instanceof PointcutPointcut) {
                        continue;
                    }

                    foreach ($pointcut->getReferencedPointcuts() as $referencedPointcut) {
                        if ($referencedPointcut->getPointcutExpression()->getPointcut()->matches($functionDefinition)) {
                            // we found a pointcut of an advice that matches!
                            // lets create a distinctive joinpoint and add the advice weaving to the pointcut.
                            // Make a clone so that there are no weird reference shenanigans
                            $pointcutExpression = clone $referencedPointcut->getPointcutExpression();
                            $joinpoint = new Joinpoint();
                            $joinpoint->setCodeHook($advice->getCodeHook());
                            $joinpoint->setStructure($functionDefinition->getStructureName());
                            $joinpoint->setTarget(Joinpoint::TARGET_METHOD);
                            $joinpoint->setTargetName($functionDefinition->getName());

                            $pointcutExpression->setJoinpoint($joinpoint);

                            // "straighten out" structure and function referenced by the pointcut to avoid regex within generated code
                            $pointcutExpression->getPointcut()->straightenExpression($functionDefinition);

                            // add the weaving pointcut into the expression
                            $pointcutExpression->setPointcut(new AndPointcut(
                                AdvisePointcut::TYPE . str_replace('\\\\', '\\', '(\\' . $advice->getQualifiedName() . ')'),
                                $pointcutExpression->getPointcut()->getType() . '(' . $pointcutExpression->getPointcut()->getExpression() . ')'
                            ));
                            $pointcutExpression->setString($pointcutExpression->getPointcut()->getExpression());

                            // add it to our result list
                            $pointcutExpressions->add($pointcutExpression);

                            // break here as we only need one, they are implicitly "or" combined
                            break;
                        }
                    }
                }
            }
        }

        return $pointcutExpressions;
    }

    /**
     * Will inject invocation code for a given function into a given piece of code.
     * Invocation code will be the instantiation of a \AppserverIo\Doppelgaenger\Entities\MethodInvocation object
     * as a basic representation of the given function
     *
     * @param string             $bucketData         Reference on the current bucket's data
     * @param FunctionDefinition $functionDefinition Definition of the function to inject invocation code into
     * @param array              $callbackChain      Chain of callbacks which is used to recursively chain calls
     *
     * @return boolean
     */
    protected function injectInvocationCode(& $bucketData, FunctionDefinition $functionDefinition, array $callbackChain)
    {

        // start building up the code
        $code = ReservedKeywords::METHOD_INVOCATION_OBJECT . ' = new \AppserverIo\Doppelgaenger\Entities\MethodInvocation(
            ';

        // add the original method call to the callback chain so it can be integrated, add it and get add the context
        if ($functionDefinition->isStatic()) {
            $contextCode = '__CLASS__';

        } else {
            $contextCode = '$this';
        }

        // iterate the callback chain and build up the code but pop the first element as we will invoke it initially
        unset($callbackChain[0]);

        // empty chain? Add the original function at least
        if (empty($callbackChain)) {
            $callbackChain[] = array($functionDefinition->getStructureName(), $functionDefinition->getName());
        }

        $code .= 'array(';
        foreach ($callbackChain as $callback) {
            // do some brushing up for the structure
            $structure = $callback[0];
            if ($structure === $functionDefinition->getStructureName()) {
                $structure = $contextCode;
            }

            // also brush up the function call to direct to the original
            if ($callback[1] === $functionDefinition->getName()) {
                $callback[1] = $functionDefinition->getName() . ReservedKeywords::ORIGINAL_FUNCTION_SUFFIX;
            }

            $code .= 'array(' . $structure . ', \'' . $callback[1] . '\'),';
        }
        $code .= '),
        ';

        // continue with the access modifiers
        $code .= $contextCode . ',
            ' . ($functionDefinition->isAbstract() ? 'true' : 'false') . ',
            ' . ($functionDefinition->isFinal() ? 'true' : 'false') . ',
            ' . ($functionDefinition->isStatic() ? 'true' : 'false') . ',
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
     * Will generate and advice chain of callbacks to the given around pointcut expressions
     *
     * @param array              $sortedPointcutExpressions Pointcut expressions sorted by their joinpoint's code hooks
     * @param FunctionDefinition $functionDefinition        Definition of the function to inject invocation code into
     *
     * @return array
     */
    protected function generateAdviceCallbacks(array $sortedPointcutExpressions, FunctionDefinition $functionDefinition)
    {

        // collect the callback chains of the involved pointcut expressions
        $callbackChain = array();
        if (isset($sortedPointcutExpressions[Around::ANNOTATION])) {
            foreach ($sortedPointcutExpressions[Around::ANNOTATION] as $aroundExpression) {
                $callbackChain = array_merge($callbackChain, $aroundExpression->getPointcut()->getCallbackChain());
            }
        }

        // filter the combined callback chain to avoid doubled calls to the original implementation
        for ($i = 0; $i < (count($callbackChain) - 1); $i ++) {
            if ($callbackChain[$i][1] === $functionDefinition->getName()) {
                unset($callbackChain[$i]);
            }
        }

        return $callbackChain;
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
            $sortedPointcutExpressions[$pointcutExpression->getJoinpoint()->getCodeHook()][] = $pointcutExpression;
        }

        return $sortedPointcutExpressions;
    }
}
