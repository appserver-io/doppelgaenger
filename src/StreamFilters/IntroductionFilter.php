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

use AppserverIo\Doppelgaenger\Entities\Lists\IntroductionList;

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
     * Filter a chunk of data by adding introductions to it
     *
     * @param string           $chunk         The data chunk to be filtered
     * @param IntroductionList $introductions List of introductions
     *
     * @return string
     */
    public function filterChunk($chunk, IntroductionList $introductions)
    {
        // Get our buckets from the stream
        $interfaceHook = '';
        $keywordNeeded = true;
        // Has to be done only once at the beginning of the definition
        if (empty($interfaceHook) && $introductions->count() > 0) {
            // Get the tokens
            $tokens = token_get_all($chunk);

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
                    $chunk = str_replace(
                        $interfaceHook . '{',
                        $interfaceHook . '{' . $useCode,
                        $chunk
                    );

                    // add the "implements" code
                    $chunk = str_replace(
                        $interfaceHook,
                        $interfaceHook . $implementsCode,
                        $chunk
                    );
                }
            }

        }

        return $chunk;
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
