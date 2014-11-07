<?php
/**
 * File containing the AdviceFilter class
 *
 * PHP version 5
 *
 * @category   Doppelgaenger
 * @package    AppserverIo\Doppelgaenger
 * @subpackage StreamFilters
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\StreamFilters;

use AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition;
use AppserverIo\Doppelgaenger\Entities\Lists\TypedListList;
use AppserverIo\Doppelgaenger\Exceptions\GeneratorException;
use AppserverIo\Doppelgaenger\Dictionaries\Placeholders;
use AppserverIo\Doppelgaenger\Dictionaries\ReservedKeywords;
use AppserverIo\Doppelgaenger\Entities\Annotations\Before;

/**
 * AppserverIo\Doppelgaenger\StreamFilters\PostconditionFilter
 *
 * This filter will buffer the input stream and add all postcondition related information at prepared locations
 * (see $dependencies)
 *
 * @category   Php-by-contract
 * @package    AppserverIo\Doppelgaenger
 * @subpackage StreamFilters
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
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

                        // Get the code for the assertions
                        $code = $this->generateCode($functionDefinition->getAdvices(Before::ANNOTATION));

                        // Insert the code
                        $placeholderHook = Placeholders::BEFORE_JOINPOINT . $functionDefinition->getName() .
                            Placeholders::PLACEHOLDER_CLOSE;
                        $bucket->data = str_replace(
                            $placeholderHook,
                            $code . $placeholderHook,
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
     * Will generate the code needed to enforce made postcondition assertions
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Lists\TypedListList $assertionLists List of assertion lists
     *
     * @return string
     */
    private function generateCode(TypedListList $assertionLists)
    {
        // We only use contracting if we're not inside another contract already
        $code = '/* BEGIN OF POSTCONDITION ENFORCEMENT */
        if (' . ReservedKeywords::CONTRACT_CONTEXT . ') {';

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

            $codeFragment = array();
            for ($j = 0; $j < $assertionIterator->count(); $j++) {

                $codeFragment[] = $assertionIterator->current()->getString();

                // Forward the iterator and tell them we got a condition
                $assertionIterator->next();
                $conditionCounter++;
            }

            // Lets insert the condition check (if there have been any)
            if (!empty($codeFragment)) {

                $code .= 'if (!((' . implode(') && (', $codeFragment) . '))){' .
                    ReservedKeywords::FAILURE_VARIABLE . ' = \'(' . str_replace(
                        '\'',
                        '"',
                        implode(') && (', $codeFragment)
                    ) . ')\';' .
                    Placeholders::PROCESSING . 'postcondition' . Placeholders::PLACEHOLDER_CLOSE . '}';
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
