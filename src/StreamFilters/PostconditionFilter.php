<?php

/**
 * \AppserverIo\Doppelgaenger\StreamFilters\PostconditionFilter
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
use AppserverIo\Doppelgaenger\Entities\Lists\TypedListList;
use AppserverIo\Doppelgaenger\Exceptions\GeneratorException;
use AppserverIo\Doppelgaenger\Dictionaries\Placeholders;
use AppserverIo\Doppelgaenger\Dictionaries\ReservedKeywords;

/**
 * This filter will buffer the input stream and add all postcondition related information at prepared locations
 * (see $dependencies)
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class PostconditionFilter extends AbstractFilter
{
    /**
     * @const integer FILTER_ORDER Order number if filters are used as a stack, higher means below others
     */
    const FILTER_ORDER = 2;

    /**
     * @var array $dependencies Other filters on which we depend
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
     * @throws \AppserverIo\Doppelgaenger\Exceptions\GeneratorException
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

            // Go through the tokens and check what we found
            $tokensCount = count($tokens);
            for ($i = 0; $i < $tokensCount; $i++) {
                // Did we find a function? If so check if we know that thing and insert the code of its preconditions.
                if (is_array($tokens[$i]) && $tokens[$i][0] === T_FUNCTION && is_array($tokens[$i + 2])) {
                    // Get the name of the function
                    $functionName = $tokens[$i + 2][1];

                    // Check if we got the function in our list, if not continue
                    $functionDefinition = $this->params->get($functionName);

                    if (!$functionDefinition instanceof FunctionDefinition) {
                        continue;

                    } else {
                        // If we use the old notation we have to insert the statement to make a copy
                        $this->injectOldCode($bucket->data, $functionDefinition);

                        // Get the code for the assertions
                        $code = $this->generateCode($functionDefinition->getAllPostconditions(), $functionName);

                        // Insert the code
                        $bucket->data = str_replace(
                            Placeholders::POSTCONDITION . $functionDefinition->getName() .
                            Placeholders::PLACEHOLDER_CLOSE,
                            $code,
                            $bucket->data
                        );

                        // "Destroy" code and function definition
                        $code = null;
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
     * Will change code to create an entry for the old object state.
     *
     * @param string                                                             $bucketData         Payload of the currently
     *                                                                                       filtered bucket
     * @param \AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition $functionDefinition Currently handled function
     *
     * @throws \AppserverIo\Doppelgaenger\Exceptions\GeneratorException
     *
     * @return boolean
     */
    protected function injectOldCode(& $bucketData, FunctionDefinition & $functionDefinition)
    {
        // Do we even need to do anything?
        if ($functionDefinition->usesOld() !== true) {
            return false;
        }
        // If the function is static it should not use the dgOld keyword as there is no state to the class!
        if ($functionDefinition->isStatic() === true) {
            throw new GeneratorException('Cannot clone class state in static method ' . $functionDefinition->getName());
        }

        // Still here? Then inject the clone statement to preserve an instance of the object prior to our call.
        $bucketData = str_replace(
            Placeholders::OLD_SETUP . $functionDefinition->getName() .
            Placeholders::PLACEHOLDER_CLOSE,
            ReservedKeywords::OLD . ' = clone $this;',
            $bucketData
        );

        // Still here? We encountered no error then.
        return true;
    }

    /**
     * Will generate the code needed to enforce made postcondition assertions
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Lists\TypedListList $assertionLists List of assertion lists
     * @param string                                                  $functionName   The name of the function for which we create the enforcement code
     *
     * @return string
     */
    protected function generateCode(TypedListList $assertionLists, $functionName)
    {
        // We only use contracting if we're not inside another contract already
        $code = '/* BEGIN OF POSTCONDITION ENFORCEMENT */
        if (' . ReservedKeywords::CONTRACT_CONTEXT . ') {' .
            ReservedKeywords::FAILURE_VARIABLE . ' = array();' .
            ReservedKeywords::UNWRAPPED_FAILURE_VARIABLE . ' = array();';

        // We need a counter to check how much conditions we got
        $conditionCounter = 0;
        $listIterator = $assertionLists->getIterator();
        for ($i = 0; $i < $listIterator->count(); $i++) {
            // Create the inner loop for the different assertions
            $assertionIterator = $listIterator->current()->getIterator();

            // Only act if we got actual entries
            if ($assertionIterator->count() === 0) {
                // increment the outer loop
                $listIterator->next();
                continue;
            }

            // collect all assertion code for assertions of this instance
            for ($j = 0; $j < $assertionIterator->count(); $j++) {
                // Code to catch failed assertions
                $code .=  $assertionIterator->current()->toCode();
                $assertionIterator->next();
                $conditionCounter++;
            }

            // generate the check for assertions results
            if ($conditionCounter > 0) {
                $code .= 'if (!empty(' . ReservedKeywords::FAILURE_VARIABLE . ') || !empty(' . ReservedKeywords::UNWRAPPED_FAILURE_VARIABLE . ')) {' .
                    Placeholders::ENFORCEMENT . $functionName . 'postcondition' . Placeholders::PLACEHOLDER_CLOSE . '
                }';
            }

            // increment the outer loop
            $listIterator->next();
        }

        // Closing bracket for contract depth check
        $code .= '}' .
            '/* END OF POSTCONDITION ENFORCEMENT */';

        // Did we get anything at all? If not only give back a comment.
        if ($conditionCounter === 0) {
            $code = '/* No postconditions for this function/method */';
        }

        return $code;
    }
}
