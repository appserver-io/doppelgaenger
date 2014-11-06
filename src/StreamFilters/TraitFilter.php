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

use AppserverIo\Doppelgaenger\StreamFilters\AbstractFilter;
use AppserverIo\Doppelgaenger\Dictionaries\Placeholders;

/**
 * AppserverIo\Doppelgaenger\StreamFilters\TraitFilter
 *
 * This filter will add the usage of given traits to a class
 *
 * @category   Appserver
 * @package    Doppelgaenger
 * @subpackage StreamFilters
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class TraitFilter extends AbstractFilter
{
    /**
     * Other filters on which we depend
     *
     * @var array $dependencies
     */
    protected $dependencies = array('Skeletonfilter');

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
    public function filter($in, $out, & $consumed, $closing)
    {
        // get a clean list of interfaces
        $traits = $this->filterParams();

        // only do something if we got traits to use
        if (empty($traits)) {

            return PSFS_PASS_ON;
        }

        // Get our buckets from the stream
        $finished = false;
        while ($bucket = stream_bucket_make_writeable($in)) {

            // Has to be done only once at the beginning of the definition
            $placeholderHook = Placeholders::FUNCTION_HOOK . Placeholders::PLACEHOLDER_CLOSE;
            if (strpos($bucket->data, $placeholderHook)) {

                // iterate all traits and build up their use statements
                $code = '';
                foreach ($traits as $trait) {

                    $code .= 'use ' . $trait . ';
                    ';
                }

                // add the use code
                $bucket->data = str_replace(
                    $placeholderHook,
                    $placeholderHook . $code,
                    $bucket->data
                );

                // tell them we are finished
                $finished = true;
            }

            // Tell them how much we already processed, and stuff it back into the output
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);

            // if we already reached our goal we can proceed to the next filter
            if ($finished === true) {

                return PSFS_PASS_ON;
            }
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
