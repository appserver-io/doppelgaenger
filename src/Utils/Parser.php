<?php

/**
 * \AppserverIo\Doppelgaenger\Utils\Parser
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

namespace AppserverIo\Doppelgaenger\Utils;

/**
 * Will provide basic parsing methods for analyzing and code structures
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class Parser
{

    /**
     * Will get the count of certain brackets within a string.
     * Will return an integer which is calculated as the number of opening brackets against closing ones.
     *
     * @param string $string      The string to search in
     * @param string $bracketType A bracket to be taken as general bracket type e.g. '('
     *
     * @return integer
     */
    public function getBracketCount($string, $bracketType)
    {
        // prepare opening and closing bracket according to bracket type
        switch ($bracketType) {
            case '(':
            case ')':
                $openingBracket = '(';
                $closingBracket = ')';
                break;

            case '{':
            case '}':
                $openingBracket = '{';
                $closingBracket = '}';
                break;

            case '[':
            case ']':
                $openingBracket = '[';
                $closingBracket = ']';
                break;

            default:
                throw new \Exception(sprintf('Unrecognized bracket type %s', $bracketType));
        }

        return substr_count($string, $openingBracket) - substr_count($string, $closingBracket);
    }

    /**
     * Will return an integer value representing the length of the first portion of the given string which is completely
     * enclosed by a certain bracket type.
     * Will return 0 if nothing is found.
     *
     * @param string  $string      String to investigate
     * @param string  $bracketType A bracket to be taken as general bracket type e.g. '('
     * @param integer $offset      Offset at which to start looking, will default to 0
     *
     * @return integer
     */
    public function getBracketSpan($string, $bracketType, $offset = 0)
    {
        // prepare opening and closing bracket according to bracket type
        switch ($bracketType) {
            case '(':
            case ')':
                $openingBracket = '(';
                $closingBracket = ')';
                break;

            case '{':
            case '}':
                $openingBracket = '{';
                $closingBracket = '}';
                break;

            case '[':
            case ']':
                $openingBracket = '[';
                $closingBracket = ']';
                break;

            default:
                throw new \Exception(sprintf('Unrecognized bracket type %s', $bracketType));
        }

        // split up the string and analyse it character for character
        $bracketCounter = null;
        $stringArray = str_split($string);
        $strlen = strlen($string);
        $firstBracket = 0;
        for ($i = $offset; $i < $strlen; $i++) {
            // count different bracket types by de- and increasing the counter
            if ($stringArray[$i] === $openingBracket) {
                if (is_null($bracketCounter)) {
                    $firstBracket = $i;
                }
                $bracketCounter = (int) $bracketCounter + 1;

            } elseif ($stringArray[$i] === $closingBracket) {
                $bracketCounter = (int) $bracketCounter - 1;
            }

            // if we reach 0 again we have a completely enclosed string
            if ($bracketCounter === 0) {
                return $i + 1 - $firstBracket;
            }
        }

        return 0;
    }
}
