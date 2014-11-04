<?php
/**
 * File containing the SkeletonFilter class
 *
 * PHP version 5
 *
 * @category   Appserver
 * @package    Doppelgaenger
 * @subpackage StreamFilters
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\StreamFilters;

use TechDivision\PBC\Entities\Definitions\FunctionDefinition;
use TechDivision\PBC\Exceptions\GeneratorException;
use TechDivision\PBC\StreamFilters\AbstractFilter;

/**
 * AppserverIo\Doppelgaenger\StreamFilters\InterfaceFilter
 *
 * This filter will add given interfaces to already defined classes
 *
 * @category   Appserver
 * @package    Doppelgaenger
 * @subpackage StreamFilters
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class InterfaceFilter extends AbstractFilter
{

    /**
     * Order number if filters are used as a stack, higher means below others
     *
     * @const integer FILTER_ORDER
     */
    const FILTER_ORDER = 00;

    /**
     * The main filter method.
     * Implemented according to \php_user_filter class. Will loop over all stream buckets, buffer them and perform
     * the needed actions.
     *
     * @param resource $in Incoming bucket brigade we need to filter
     * @param resource $out Outgoing bucket brigade with already filtered content
     * @param integer $consumed The count of altered characters as buckets pass the filter
     * @param boolean $closing Is the stream about to close?
     *
     * @throws \TechDivision\PBC\Exceptions\GeneratorException
     *
     * @return integer
     *
     * @link http://www.php.net/manual/en/php-user-filter.filter.php
     */
    public function filter($in, $out, & $consumed, $closing)
    {
        // get a clean list of interfaces
        $interfaces = $this->filterParams();

        // only do something if we got interfaces to implement
        if (empty($interfaces)) {

            return PSFS_PASS_ON;
        }

        // Get our buckets from the stream
        $interfaceHook = '';
        $abortNow = false;
        while ($bucket = stream_bucket_make_writeable($in)) {

            // Has to be done only once at the beginning of the definition
            if (empty($interfaceHook)) {

                // Get the tokens
                $tokens = token_get_all($bucket->data);

                // Go through the tokens and check what we found
                $tokensCount = count($tokens);
                for ($i = 0; $i < $tokensCount; $i++) {

                    // We need something to hook into, right after class header seems fine
                    if (is_array($tokens[$i]) && $tokens[$i][0] === T_CLASS) {

                        for ($j = $i; $j < $tokensCount; $j++) {

                            // If we got the opening bracket we can break
                            if ($tokens[$j] === '{' || $tokens[$j][0] === T_CURLY_OPEN) {

                                break;
                            }

                            if (is_array($tokens[$j])) {

                                $interfaceHook .= $tokens[$j][1];
                            } else {

                                $interfaceHook .= $tokens[$j];
                            }
                        }

                        // Insert the placeholder for our function hook.
                        // All following injects into the structure body will rely on it
                        $bucket->data = str_replace(
                            $interfaceHook,
                            $interfaceHook . ' implements ' . implode(', ', $interfaces),
                            $bucket->data
                        );
                    }
                }

            } else {

                $abortNow = true;
            }

            // Tell them how much we already processed, and stuff it back into the output
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);

            // if we already reached our goal we can proceed to the next filter
            if ($abortNow === true) {

                return PSFS_PASS_ON;
            }
        }

        // If the interface hook is empty we failed and should stop what we are doing
        if (empty($interfaceHook)) {

            throw new GeneratorException();
        }

        return PSFS_PASS_ON;
    }

    /**
     * Will filter the given params and create a clean array of interface names from them
     *
     * @return array
     */
    protected function filterParams()
    {
        $interfaces = array();

        // filter the params
        if (is_array($this->params)) {

            $interfaces = $this->params;

        } else {

            $interfaces[] = $this->params;
        }

        // filter out everything which might not be right
        foreach ($interfaces as $key => $interfaceCandidate) {

            if (!is_string($interfaceCandidate)) {

                unset($interfaces[$key]);
            }
        }

        return $interfaces;
    }
}
