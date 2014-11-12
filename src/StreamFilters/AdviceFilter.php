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
use AppserverIo\Doppelgaenger\Dictionaries\Placeholders;

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

                        // get the pointcuts which are associated with this function already and inject the code
                        $sortedFunctionPointcuts = $this->sortPointcutExpressions($functionDefinition->getPointcutExpressions());
                        $tmp = $this->injectAdviceCode($bucket->data, $sortedFunctionPointcuts, $functionName);

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

            foreach ($pointcutExpressions as $pointcutExpression) {

                // Insert the code
                $placeholderName = strtoupper($joinpoint) . '_JOINPOINT';
                $placeholderHook = constant('\AppserverIo\Doppelgaenger\Dictionaries\Placeholders::' . $placeholderName) .
                    $functionName . Placeholders::PLACEHOLDER_CLOSE;
                $bucketData = str_replace(
                    $placeholderHook,
                    $pointcutExpression->getString() . $placeholderHook,
                    $bucketData
                );
            }
        }

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

            foreach ($pointcutExpression->getJoinpoints() as $joinpoint) {

                $sortedPointcutExpressions[$joinpoint->codeHook][] = $pointcutExpression;
            }
        }

        return $sortedPointcutExpressions;
    }
}
