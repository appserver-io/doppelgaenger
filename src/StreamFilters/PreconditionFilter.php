<?php

/**
 * \AppserverIo\Doppelgaenger\StreamFilters\PreconditionFilter
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
use AppserverIo\Doppelgaenger\Entities\Lists\FunctionDefinitionList;
use AppserverIo\Doppelgaenger\Entities\Lists\TypedListList;
use AppserverIo\Doppelgaenger\Dictionaries\Placeholders;
use AppserverIo\Doppelgaenger\Dictionaries\ReservedKeywords;

/**
 * This filter will buffer the input stream and add all precondition related information at prepared locations
 * (see $dependencies)
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class PreconditionFilter extends AbstractFilter
{

    /**
     * @const integer FILTER_ORDER Order number if filters are used as a stack, higher means below others
     */
    const FILTER_ORDER = 1;

    /**
     * @var array $dependencies Other filters on which we depend
     */
    protected $dependencies = array('SkeletonFilter');

    /**
     * Filter a chunk of data by adding precondition checks
     *
     * @param string                 $chunk               The data chunk to be filtered
     * @param FunctionDefinitionList $functionDefinitions Definition of the structure the chunk belongs to
     *
     * @return string
     */
    public function filterChunk($chunk, FunctionDefinitionList $functionDefinitions)
    {
        // Get the tokens
        $tokens = token_get_all($chunk);

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
                    // Get the code for the assertions
                    $code = $this->generateCode($functionDefinition->getAllPreconditions(), $functionName);

                    // Insert the code
                    $chunk = str_replace(
                        Placeholders::PRECONDITION . $functionDefinition->getName() .
                        Placeholders::PLACEHOLDER_CLOSE,
                        $code,
                        $chunk
                    );

                    // "Destroy" code and function definition
                    $code = null;
                    $functionDefinition = null;
                }
            }
        }

        // Tell them how much we already processed, and stuff it back into the output
        return $chunk;
    }

    /**
     * Will generate the code needed to enforce made precondition assertions
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Lists\TypedListList $assertionLists List of assertion lists
     * @param string                                                  $functionName   The name of the function for which we create the enforcement code
     *
     * @return string
     */
    protected function generateCode(TypedListList $assertionLists, $functionName)
    {
        // We only use contracting if we're not inside another contract already
        $code = '/* BEGIN OF PRECONDITION ENFORCEMENT */
            if (' . ReservedKeywords::CONTRACT_CONTEXT . ') {
                ' . ReservedKeywords::PASSED_ASSERTION_FLAG . ' = false;
                ' . ReservedKeywords::FAILURE_VARIABLE . ' = array();
                ' . ReservedKeywords::UNWRAPPED_FAILURE_VARIABLE . ' = array();
                ';

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

            // create a wrap around assuring that inherited conditions get or-combined
            $code .= '
                if (' . ReservedKeywords::PASSED_ASSERTION_FLAG . ' === false) {
                    ';

            // iterate through the conditions for this certain instance
            for ($j = 0; $j < $assertionIterator->count(); $j++) {
                $conditionCounter++;

                // Code to catch failed assertions
                $code .= $assertionIterator->current()->toCode();
                $assertionIterator->next();
            }

            // close the or-combined wrap
            $code .= '    if (empty(' . ReservedKeywords::FAILURE_VARIABLE . ') && empty(' . ReservedKeywords::UNWRAPPED_FAILURE_VARIABLE . ')) {
                        ' . ReservedKeywords::PASSED_ASSERTION_FLAG . ' = true;
                    }
                }
                ';

            // increment the outer loop
            $listIterator->next();
        }

        // Preconditions need or-ed conditions so we make sure only one condition list gets checked
        $code .= 'if (' . ReservedKeywords::PASSED_ASSERTION_FLAG . ' === false){
                    ' . Placeholders::ENFORCEMENT . $functionName . 'precondition' . Placeholders::PLACEHOLDER_CLOSE . '
                }
            }
            /* END OF PRECONDITION ENFORCEMENT */';

        // If there were no assertions we will just return a comment
        if ($conditionCounter === 0) {
            return '/* No preconditions for this function/method */';
        }

        return $code;
    }
}
