<?php

/**
 * \AppserverIo\Doppelgaenger\StreamFilters\IntroductionFilter
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

/**
 * This filter will add given interfaces to already defined classes
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class IntroductionFilter extends AbstractFilter
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
        // get all the introductions of a structure definition
        $introductions = $this->params;

        // Get our buckets from the stream
        $interfaceHook = '';
        $keywordNeeded = true;
        while ($bucket = stream_bucket_make_writeable($in)) {
            // Has to be done only once at the beginning of the definition
            if (empty($interfaceHook) && $introductions->count() > 0) {
                // Get the tokens
                $tokens = token_get_all($bucket->data);

                // Go through the tokens and check what we found
                $tokensCount = count($tokens);
                for ($i = 0; $i < $tokensCount; $i++) {
                    // We need something to hook into, right after class header seems fine
                    if (is_array($tokens[$i]) && $tokens[$i][0] === T_CLASS && $tokens[$i - 1][0] !== T_PAAMAYIM_NEKUDOTAYIM) {
                        for ($j = $i; $j < $tokensCount; $j++) {
                            // If we got the opening bracket we can break
                            if ($tokens[$j] === '{' || $tokens[$j][0] === T_CURLY_OPEN) {
                                break;
                            }

                            if (is_array($tokens[$j])) {
                                // we have to check if there already are interfaces
                                if ($tokens[$j][0] === T_IMPLEMENTS) {
                                    $keywordNeeded = false;
                                }

                                $interfaceHook .= $tokens[$j][1];

                            } else {
                                $interfaceHook .= $tokens[$j];
                            }
                        }

                        // build up the injected code and make the injection
                        if ($keywordNeeded) {
                            $implementsCode = ' implements ';

                        } else {
                            $implementsCode = ', ';
                        }
                        $useCode = '';
                        $interfaces = array();
                        foreach ($introductions as $introduction) {
                            $interfaces[] = $introduction->getInterface();

                            // build up code for the trait usage
                            $useCode .= 'use ' . $introduction->getImplementation() . ';
                            ';
                        }
                        $implementsCode .= implode(', ', $interfaces);

                        // add the "use" code
                        $bucket->data = str_replace(
                            $interfaceHook . '{',
                            $interfaceHook . '{' . $useCode,
                            $bucket->data
                        );

                        // add the "implements" code
                        $bucket->data = str_replace(
                            $interfaceHook,
                            $interfaceHook . $implementsCode,
                            $bucket->data
                        );
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
