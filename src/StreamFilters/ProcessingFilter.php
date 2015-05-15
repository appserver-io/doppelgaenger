<?php

/**
 * \AppserverIo\Doppelgaenger\StreamFilters\ProcessingFilter
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
class ProcessingFilter extends AbstractFilter
{
    /**
     * @const integer FILTER_ORDER Order number if filters are used as a stack, higher means below others
     */
    const FILTER_ORDER = 00;

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
                // Did we find a function? If so check if we know that thing and insert the code of its preconditions
                if (is_array($tokens[$i]) && $tokens[$i][0] === T_FUNCTION && is_array($tokens[$i + 2])) {
                    // Get the name of the function
                    $functionName = $tokens[$i + 2][1];

                    // Check if we got the function in our list, if not continue
                    $functionDefinition = $this->params->get($functionName);

                    if (!$functionDefinition instanceof FunctionDefinition) {
                        continue;

                    } else {
                        // Get the code for the needed call
                        $code = $this->generateCode($functionDefinition);

                        // Insert the code
                        $bucket->data = str_replace(
                            Placeholders::AROUND_JOINPOINT . $functionDefinition->getName() .
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
     * Will generate the code to call the original method logic
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition $functionDefinition The function
     *
     * @return string
     */
    protected function generateCode(FunctionDefinition $functionDefinition)
    {
        // Build up the call to the original function
        return ReservedKeywords::RESULT . ' = ' . $functionDefinition->getHeader('call', ReservedKeywords::ORIGINAL_FUNCTION_SUFFIX, false, true) . ';';
    }
}
