<?php
/**
 * File containing the AbstractFilter class
 *
 * PHP version 5
 *
 * @category   Appserver
 * @package    AppserverIo\Doppelgaenger
 * @subpackage StreamFilters
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\StreamFilters;

use AppserverIo\Doppelgaenger\Interfaces\StreamFilterInterface;

/**
 * AppserverIo\Doppelgaenger\StreamFilters\AbstractFilter
 *
 * This abstract class provides a clean parent class for custom stream filters
 *
 * @category   Appserver
 * @package    AppserverIo\Doppelgaenger
 * @subpackage StreamFilters
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
abstract class AbstractFilter extends \php_user_filter implements StreamFilterInterface
{
    /**
     * Other filters on which we depend
     *
     * @var array $dependencies
     */
    protected $dependencies = array();

    /**
     * Name of the filter (done as seen in \php_user_filter class)
     *
     * @var string $filtername
     *
     * @link http://www.php.net/manual/en/class.php-user-filter.php
     */
    public $filtername = __CLASS__;

    /**
     * The parameter(s) we get passed when appending the filter to a stream
     *
     * @var mixed $params
     *
     * @link http://www.php.net/manual/en/class.php-user-filter.php
     */
    public $params;

    /**
     * A member to be used to buffer stream buckets into if needed
     *
     * @var string $bucketBuffer
     */
    protected $bucketBuffer = '';

    /**
     * Will collect the content of ongoing tokens until a certain token is reached.
     * Will return collected content as string
     *
     * @param array   $tokens     Array of tokens to collect from
     * @param integer $startIndex The index from which to collect
     * @param string  $stopToken  The token at which collection stops, token will not be included in the result
     *
     * @return string
     */
    protected function collectTillToken(array $tokens, $startIndex, $stopToken)
    {
        $result = '';

        // Go through the tokens and collect what we find
        $tokensCount = count($tokens);
        for ($i = $startIndex; $i < $tokensCount; $i++) {

            // upon reaching the stop token we will exit
            if (is_array($tokens[$i]) && $tokens[$i][0] === $stopToken) {

                break;
            }

            // collect what we find
            if (is_array($tokens[$i])) {

                $result .= $tokens[$i][1];

            } else {

                $result .= $tokens[$i];
            }
        }

        return $result;
    }

    /**
     * Not implemented yet
     *
     * @throws \Exception
     *
     * @return void
     */
    public function dependenciesMet()
    {
        throw new \Exception();
    }

    /**
     * The main filter method.
     * Implemented according to \php_user_filter class. Will loop over all stream buckets, buffer them and perform
     * the needed actions by calling the child's filterContent() method.
     *
     * @param resource $in        Incoming bucket brigade we need to filter
     * @param resource $out       Outgoing bucket brigade with already filtered content
     * @param integer  &$consumed The count of altered characters as buckets pass the filter
     * @param boolean  $closing   Is the stream about to close?
     *
     * @throws \Exception
     * @throws \PHPParser_Error
     *
     * @return integer
     *
     * @link http://www.php.net/manual/en/php-user-filter.filter.php
     */
    public function filter($in, $out, &$consumed, $closing)
    {
        // Before we get started we might prepare something
        $this->prepare();

        // Get our buckets from the stream
        $firstIteration = true;
        while ($bucket = stream_bucket_make_writeable($in)) {

            // Lets call the firstRun() hook on the first run
            if ($firstIteration === true) {

                $this->firstRun();
                $firstIteration = false;

                // We have to fill the buffer with our first bucket
                $this->bucketBuffer = $bucket->data;
            }

            // Get the filtered content from the current bucket(s)
            $filteredContent = $this->filterContent($this->bucketBuffer);

            // If we got a string the filtering has been finished for this buffer stack,
            // we can append the content and clear the buffer.
            // If not we have to keep buffering and make another iteration.
            if (is_string($filteredContent)) {

                // Tell them how much we already processed
                $contentLength = strlen($filteredContent);
                $consumed += $contentLength;

                // Alter the current bucket, we will use it as a "new" bucket to append
                $bucket->data = $filteredContent;
                $bucket->datalen = $contentLength;
                // Append the altered bucket
                stream_bucket_append($out, $bucket);

                // Clear the buffer
                $this->bucketBuffer = '';

            } else {

                // Buffer the bucket data for further use
                $this->bucketBuffer .= $bucket->data;
            }
        }

        // Call the finish() hook of potential child filters
        $this->finish($out);

        return PSFS_PASS_ON;
    }

    /**
     * Preparation hook which is intended to be called before the first filter() iteration.
     * We will provide an empty implementation here, to not force the hook on filter classes.
     * So override if needed.
     *
     * @return void
     */
    public function prepare()
    {
        // Do nothing here
    }

    /**
     * Preparation hook which is intended to be called at the start of the first filter() iteration.
     * We will provide an empty implementation here, to not force the hook on filter classes.
     * So override if needed.
     *
     * @return void
     */
    public function firstRun()
    {
        // Do nothing here
    }

    /**
     * Hook to be called right before the filter() method finishes.
     * We will provide an empty implementation here, to not force the hook on filter classes.
     * So override if needed.
     *
     * @param resource &$out Outgoing bucket brigade with already filtered content
     *
     * @return void
     */
    public function finish(&$out)
    {
        // Do nothing here
    }

    /**
     * Getter for the bucketBuffer property
     *
     * @return string
     */
    public function getBucketBuffer()
    {
        return $this->bucketBuffer;
    }

    /**
     * Will return the dependency array
     *
     * @return array
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /**
     * Will return the order number the concrete filter has been constantly assigned
     *
     * @return integer
     */
    public function getFilterOrder()
    {
        return self::FILTER_ORDER;
    }
}
