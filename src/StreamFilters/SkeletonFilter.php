<?php

/**
 * \AppserverIo\Doppelgaenger\StreamFilters\SkeletonFilter
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

use AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition;
use AppserverIo\Doppelgaenger\Exceptions\GeneratorException;
use AppserverIo\Doppelgaenger\Dictionaries\Placeholders;
use AppserverIo\Doppelgaenger\Dictionaries\ReservedKeywords;
use AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface;
use AppserverIo\Doppelgaenger\Utils\Parser;
use AppserverIo\Doppelgaenger\Entities\Definitions\ClassDefinition;
use AppserverIo\Doppelgaenger\Entities\Definitions\InterfaceDefinition;
use AppserverIo\Doppelgaenger\Entities\Definitions\TraitDefinition;

/**
 * This filter is the most important one!
 * It will analyze the need to act upon the content we get and prepare placeholder for coming filters so they
 * do not have to do the analyzing part again.
 * This placeholder system also makes them highly optional, configur- and interchangeable.
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class SkeletonFilter extends AbstractFilter
{

    /**
     * Order number if filters are used as a stack, higher means below others
     *
     * @const integer FILTER_ORDER
     */
    const FILTER_ORDER = 0;

    /**
     * Will filter portions of incoming stream content.
     * Will always contain false to enforce buffering of all buckets.
     *
     * @param string $content The content to be filtered
     *
     * @return boolean
     */
    public function filterContent($content)
    {
        return false;
    }

    /**
     * Preparation hook which is intended to be called at the start of the first filter() iteration.
     * We will inject the original path hint here
     *
     * @param string $bucketData Payload of the first filtered bucket
     *
     * @return void
     */
    public function firstBucket(&$bucketData)
    {
        $this->injectOriginalPathHint($bucketData, $this->structureDefinition->getPath());
    }

    /**
     * Will find the index of the last character within a structure
     *
     * @param string $code          The structure code to search in
     * @param string $structureName Name of the structure to get the last index for
     * @param string $structureType Type of the structure in question
     *
     * @return integer
     */
    protected function findLastStructureIndex($code, $structureName, $structureType)
    {
        // determine which keyword we should search for
        switch ($structureType) {
            case InterfaceDefinition::TYPE:
                $structureKeyword = 'interface';
                break;

            case TraitDefinition::TYPE:
                $structureKeyword = 'trait';
                break;

            default:
                $structureKeyword = 'class';
                break;
        }

        // cut everything in front of the first bracket so we have a better start
        $matches = array();
        preg_match('/.*' . $structureKeyword . '\s+' . $structureName . '.+?{/s', $code, $matches);
        if (count($matches) != 1) {
            throw new GeneratorException(sprintf('Could not find last index for stucture %s. Cannot generate proxy skeleton.', $structureName));
        }

        $offset = (strlen(reset($matches)) - 1);
        // get a parser util and get the bracket span
        $parserUtil = new Parser();
        $structureSpan = $parserUtil->getBracketSpan($code, '{', $offset);

        return (($structureSpan + $offset) - 1);
    }

    /**
     * Preparation hook which is intended to be called at the start of the first filter() iteration.
     * We will inject the original path hint here
     *
     * @return void
     */
    public function finish()
    {
        // we have to substitute magic __DIR__ and __FILE__ constants
        $this->substituteLocationConstants($this->bucketBuffer, $this->structureDefinition->getPath());

        // substitute the original function declarations for the renamed ones
        $this->substituteFunctionHeaders($this->bucketBuffer, $this->structureDefinition);

        // mark the end of the structure as this is an important hook for other things to be woven
        $lastIndex = $this->findLastStructureIndex($this->bucketBuffer, $this->structureDefinition->getName(), $this->structureDefinition->getType());
        $this->bucketBuffer = substr_replace($this->bucketBuffer, Placeholders::STRUCTURE_END, $lastIndex, 0);

        // inject the code for the function skeletons
        $this->injectFunctionSkeletons($this->bucketBuffer, $this->structureDefinition, true);
    }

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
     * @throws \AppserverIo\Doppelgaenger\Exceptions\GeneratorException
     *
     * @return integer
     *
     * @link http://www.php.net/manual/en/php-user-filter.filter.php
     */
    public function filter($in, $out, &$consumed, $closing)
    {
        // make the params more prominent
        $this->structureDefinition = $this->params;

        // use the parent filter method to allow for proper hook usage
        return parent::filter($in, $out, $consumed, $closing);
    }

    /**
     * Will inject condition checking code in front and behind the functions body.
     *
     * @param string                                                             $bucketData          Payload of the currently filtered bucket
     * @param \AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface $structureDefinition The original path we have to place as our constants
     * @param boolean                                                            $beautify            Whether or not the injected code should be beautified first
     *
     * @return boolean
     */
    protected function injectFunctionSkeletons(& $bucketData, StructureDefinitionInterface $structureDefinition, $beautify = false)
    {

        // generate the skeleton code for all known functions
        $functionSkeletonsCode = '';
        foreach ($structureDefinition->getFunctionDefinitions() as $functionDefinition) {
            // we do not have to act on abstract methods
            if ($functionDefinition->isAbstract()) {
                continue;
            }

            // __get and __set need some special steps so we can inject our own logic into them
            $injectNeeded = false;
            if ($functionDefinition->getName() === '__get' || $functionDefinition->getName() === '__set') {
                $injectNeeded = true;
            }

            // get the code used before the original body
            $functionSkeletonsCode .= $this->generateSkeletonCode($injectNeeded, $functionDefinition);
        }

        // inject the new code at the end of the original structure body
        $bucketData = str_replace(Placeholders::STRUCTURE_END, Placeholders::STRUCTURE_END . $functionSkeletonsCode, $bucketData);

        // if we are still here we seem to have succeeded
        return true;
    }

    /**
     * Will generate the skeleton code for the passed function definition.
     * Will result in a string resembling the following example:
     *
     *      <FUNCTION_DOCBLOCK>
     *      <FUNCTION_MODIFIERS> function <FUNCTION_NAME>(<FUNCTION_PARAMS>)
     *      {
     *          $dgStartLine = <FUNCTION_START_LINE>;
     *          $dgEndLine = <FUNCTION_END_LINE>;
     *          / DOPPELGAENGER_FUNCTION_BEGIN_PLACEHOLDER <FUNCTION_NAME> /
     *          / DOPPELGAENGER_BEFORE_JOINPOINT <FUNCTION_NAME> /
     *          $dgOngoingContract = \AppserverIo\Doppelgaenger\ContractContext::open();
     *          / DOPPELGAENGER_INVARIANT_PLACEHOLDER /
     *          / DOPPELGAENGER_PRECONDITION_PLACEHOLDER <FUNCTION_NAME> /
     *          / DOPPELGAENGER_OLD_SETUP_PLACEHOLDER <FUNCTION_NAME> /
     *          $dgResult = null;
     *          try {
     *              / DOPPELGAENGER_AROUND_JOINPOINT <FUNCTION_NAME> /
     *
     *          } catch (\Exception $dgThrownExceptionObject) {
     *              / DOPPELGAENGER_AFTERTHROWING_JOINPOINT <FUNCTION_NAME> /
     *
     *              // rethrow the exception
     *              throw $dgThrownExceptionObject;
     *
     *          } finally {
     *              / DOPPELGAENGER_AFTER_JOINPOINT <FUNCTION_NAME> /
     *
     *          }
     *          / DOPPELGAENGER_POSTCONDITION_PLACEHOLDER <FUNCTION_NAME> /
     *          / DOPPELGAENGER_INVARIANT_PLACEHOLDER /
     *          if ($dgOngoingContract) {
     *              \AppserverIo\Doppelgaenger\ContractContext::close();
     *          } / DOPPELGAENGER_AFTERRETURNING_JOINPOINT <FUNCTION_NAME> /
     *
     *          return $dgResult;
     *      }
     *
     * @param boolean            $injectNeeded       Determine if we have to use a try...catch block
     * @param FunctionDefinition $functionDefinition The function definition object
     *
     * @return string
     */
    protected function generateSkeletonCode($injectNeeded, FunctionDefinition $functionDefinition)
    {

        // first of all: the docblock
        $code = '
        ' . $functionDefinition->getDocBlock() . '
        ' . $functionDefinition->getHeader('definition') . '
        {
            ' . ReservedKeywords::START_LINE_VARIABLE . ' = ' . (integer) $functionDefinition->getStartLine() . ';
            ' . ReservedKeywords::END_LINE_VARIABLE . ' = ' . (integer) $functionDefinition->getEndLine() . ';
            ' . Placeholders::FUNCTION_BEGIN . $functionDefinition->getName() . Placeholders::PLACEHOLDER_CLOSE . '
            ';

        // right after: the "before" join-point
        $code .= Placeholders::BEFORE_JOINPOINT . $functionDefinition->getName() . Placeholders::PLACEHOLDER_CLOSE . '
            ';

        // open the contract context so we are able to avoid endless recursion
        $code .= ReservedKeywords::CONTRACT_CONTEXT . ' = \AppserverIo\Doppelgaenger\ContractContext::open();
            ';

        // Invariant is not needed in private or static functions.
        // Also make sure that there is none in front of the constructor check
        if ($functionDefinition->getVisibility() !== 'private' &&
            !$functionDefinition->isStatic() && $functionDefinition->getName() !== '__construct'
        ) {
            $code .= Placeholders::INVARIANT_CALL_START . '
            ';
        }

        $code .= Placeholders::PRECONDITION . $functionDefinition->getName() . Placeholders::PLACEHOLDER_CLOSE . '
            ' . Placeholders::OLD_SETUP . $functionDefinition->getName() . Placeholders::PLACEHOLDER_CLOSE. '
            ';

        // we will wrap code execution in order to provide a "finally" and "after throwing" placeholder hook.
        // we will also predefine the result as NULL to avoid warnings
        $code .= ReservedKeywords::RESULT . ' = null;
            try {
                ' .  Placeholders::AROUND_JOINPOINT . $functionDefinition->getName() . Placeholders::PLACEHOLDER_CLOSE . '
        ';

        // add the second part of the try/catch/finally block
        $code .= '
            } catch (\Exception ' . ReservedKeywords::THROWN_EXCEPTION_OBJECT . ') {
                ' . Placeholders::AFTERTHROWING_JOINPOINT . $functionDefinition->getName() . Placeholders::PLACEHOLDER_CLOSE . '
                // rethrow the exception
                throw ' . ReservedKeywords::THROWN_EXCEPTION_OBJECT . ';
            } finally {
        ';

        // if we have to inject additional code, we might do so here
        if ($injectNeeded === true) {
            $code .= Placeholders::METHOD_INJECT . $functionDefinition->getName() . Placeholders::PLACEHOLDER_CLOSE;
        }

        // finish of the block
        $code .= '        ' . Placeholders::AFTER_JOINPOINT . $functionDefinition->getName() . Placeholders::PLACEHOLDER_CLOSE . '
            }
        ';

        // now just place all the other placeholder for other filters to come
        $code .= '    ' . Placeholders::POSTCONDITION . $functionDefinition->getName() . Placeholders::PLACEHOLDER_CLOSE;

        // Invariant is not needed in private or static functions
        if ($functionDefinition->getVisibility() !== 'private' && !$functionDefinition->isStatic()) {
            $code .= '
            ' . Placeholders::INVARIANT_CALL_END . '
            ';
        }

        // close of the contract context
        $code .= 'if (' . ReservedKeywords::CONTRACT_CONTEXT . ') {
                \AppserverIo\Doppelgaenger\ContractContext::close();
            }
        ';

        // last of all: the "after returning" join-point and the final return from the proxy
        $code .= '    ' . Placeholders::AFTERRETURNING_JOINPOINT . $functionDefinition->getName() . Placeholders::PLACEHOLDER_CLOSE . '
            return ' . ReservedKeywords::RESULT . ';
        }
        ';

        return $code;
    }

    /**
     * Will substitute all function headers (we know about) with function headers indicating an original implementation by appending
     * a specific suffix
     *
     * @param string                                                             $bucketData          Payload of the currently filtered bucket
     * @param \AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface $structureDefinition The original path we have to place as our constants
     *
     * @return boolean
     */
    protected function substituteFunctionHeaders(& $bucketData, StructureDefinitionInterface $structureDefinition)
    {
        // is there event anything to substitute?
        if ($structureDefinition->getFunctionDefinitions()->count() <= 0) {
            return true;
        }

        // first of all we have to collect all functions we have to substitute
        $functionSubstitutes = array();
        $functionPatterns = array();
        foreach ($structureDefinition->getFunctionDefinitions() as $functionDefinition) {
            // we do not have to act on abstract methods
            if ($functionDefinition->isAbstract()) {
                continue;
            }

            $functionPatterns[] = '/function\s+' . $functionDefinition->getName() . '\s*\(/';
            $functionSubstitutes[] = 'function ' . $functionDefinition->getName() . ReservedKeywords::ORIGINAL_FUNCTION_SUFFIX . '(';
        }

        // do the actual replacing and propagate the result in success
        $result = preg_replace($functionPatterns, $functionSubstitutes, $bucketData);
        if (!is_null($result)) {
            $bucketData = $result;
            return true;
        }

        // still here? That seems to be wrong
        return false;
    }

    /**
     * Will substitute all magic __DIR__ and __FILE__ constants with our prepared substitutes to
     * emulate original original filesystem context when in cache folder.
     *
     * @param string $bucketData Payload of the currently filtered bucket
     * @param string $file       The original path we have to place as our constants
     *
     * @return boolean
     */
    protected function substituteLocationConstants(& $bucketData, $file)
    {
        $dir = dirname($file);
        // Inject the code
        $bucketData = str_replace(
            array('__DIR__', '__FILE__'),
            array('\'' . $dir . '\'', '\'' . $file . '\''),
            $bucketData
        );

        // Still here? Success then
        return true;
    }

    /**
     * Will inject a placeholder that is used to store metadata about the original file
     *
     * @param string $bucketData Payload of the currently filtered bucket
     * @param string $file       The original file path we have to inject
     *
     * @return boolean
     */
    protected function injectOriginalPathHint(& $bucketData, $file)
    {
        // Do need to do this?
        if (strpos($bucketData, '<?php') === false) {
            return false;
        }

        // Build up the needed code for our hint
        $code = ' ' . Placeholders::PLACEHOLDER_OPEN . Placeholders::ORIGINAL_PATH_HINT . $file . '#' .
            filemtime(
                $file
            ) . Placeholders::ORIGINAL_PATH_HINT . Placeholders::PLACEHOLDER_CLOSE;

        // Inject the code
        $index = strpos($bucketData, '<?php');
        $bucketData = substr_replace($bucketData, $code, $index + 5, 0);

        // Still here? Success then.
        return true;
    }
}
