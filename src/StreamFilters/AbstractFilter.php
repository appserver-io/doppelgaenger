<?php

/**
 * \AppserverIo\Doppelgaenger\StreamFilters\AbstractFilter
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

use AppserverIo\Doppelgaenger\Interfaces\StreamFilterInterface;
use Monolog\Handler\error_log;

/**
 * This abstract class provides a clean parent class for custom stream filters
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
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
     * Hook to be called right before the stream closes.
     * We will provide an empty implementation here, to not force the hook on filter classes.
     * So override if needed.
     *
     * @return void
     */
    public function cleanup()
    {
        // Do nothing here
    }

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
        throw new \Exception(sprintf('%s not implemented yet', __METHOD__));
    }

    /**
     * The main filter method.
     * Implemented according to \php_user_filter class. Will loop over all stream buckets, buffer them and perform
     * the needed actions by calling the child's filterContent() method.
     *
     * @param resource $in       Incoming bucket brigade we need to filter
     * @param resource $out      Outgoing bucket brigade with already filtered content
     * @param integer  $consumed The count of altered characters as buckets pass the filter
     * @param boolean  $closing  Is the stream about to close?
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
        // before we get started we might prepare something
        $this->prepare();

        // allow for cleanup on closing streams
        if ($closing == true) {
            $this->cleanup();
            return PSFS_FEED_ME;
        }

        // get our buckets from the stream
        $firstIteration = true;
        while ($bucket = stream_bucket_make_writeable($in)) {
            // lets call the firstBucket() hook on the first run
            if ($firstIteration === true) {
                $this->firstBucket($bucket->data);
                $firstIteration = false;
            }

            // we have to fill the buffer with our current bucket
            $this->bucketBuffer .= $bucket->data;

            // get the filtered content from the current bucket(s)
            $filteredContent = $this->filterContent($this->bucketBuffer);

            // if we got a string the filtering has been finished for this buffer stack,
            // we can append the content and clear the buffer.
            // If not we have to keep buffering and make another iteration.
            if (is_string($filteredContent)) {
                // alter the current bucket, we will use it as a "new" bucket to append
                $bucket->data = $filteredContent;
                // append the altered bucket
                stream_bucket_append($out, $bucket);

                // restart buffering
                $this->bucketBuffer = '';
            }

            // tell them what we have already consumed and save a bucket for later
            $consumed += $bucket->datalen;
            $lastBucket = clone $bucket;
        }

        // call the finish() hook of potential child filters
        $this->finish();

        // if there is still content in the buffer we have to append it as well
        $bufferLength = strlen($this->bucketBuffer);
        if ($bufferLength > 0) {
            // attach the remaining buffer content to the stream
            $lastBucket->data = $this->bucketBuffer;
            stream_bucket_append($out, $lastBucket);
        }

        return PSFS_PASS_ON;
    }

    /**
     * Will filter portions of incoming stream content.
     * Should return FALSE if filter run has to be repeated with extended buffer or TRUE if the currently available
     * buffer did already contain all needed information.
     *
     * @param string $content The content to be filtered
     *
     * @return boolean
     */
    public function filterContent($content)
    {
        return true;
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
     * @param string $bucketData Payload of the first filtered bucket
     *
     * @return void
     */
    public function firstBucket(&$bucketData)
    {
        // Do nothing here
    }

    /**
     * Hook to be called right before the filter() method finishes.
     * We will provide an empty implementation here, to not force the hook on filter classes.
     * So override if needed.
     *
     * @return void
     */
    public function finish()
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
