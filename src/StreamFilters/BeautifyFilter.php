<?php

/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage StreamFilters
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\StreamFilters;

use AppserverIo\Doppelgaenger\Exceptions\GeneratorException;

/**
 * AppserverIo\Doppelgaenger\StreamFilters\BeautifyFilter
 *
 * This filter will buffer the input stream, check it for php syntax errors and beautify it using
 * the nikic/php-parser lib
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage StreamFilters
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class BeautifyFilter extends AbstractFilter
{

    /**
     * @const integer FILTER_ORDER Order number if filters are used as a stack, higher means below others
     */
    const FILTER_ORDER = 99;

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
     * As we depend on a fully buffered bucket brigade we will do all the work here.
     * We will pretty-print the buffer and write the result as one big bucket into the stream
     *
     * @param resource $out Outgoing bucket brigade with already filtered content
     *
     * @return void
     * @throws \AppserverIo\Doppelgaenger\Exceptions\GeneratorException
     */
    public function finish(&$out)
    {
        // Beautify all the buckets!
        $parser = new \PHPParser_Parser(new \PHPParser_Lexer);
        $prettyPrinter = new \PHPParser_PrettyPrinter_Default;

        try {
            // parse
            $stmts = $parser->parse($this->bucketBuffer);

            $data = '<?php ' . $prettyPrinter->prettyPrint($stmts);

        } catch (PHPParser_Error $e) {

            throw new GeneratorException($e->getMessage());
        }

        // Refill the bucket with the beautified data
        // Do not forget to set the length!
        $bigBucket = new \Bucket();
        $bigBucket->data = $data;
        $bigBucket->datalen = strlen($data);

        // Only append our big bucket
        stream_bucket_append($out, $bigBucket);
    }
}
