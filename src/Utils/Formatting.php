<?php

/**
 * \AppserverIo\Doppelgaenger\Utils\Formatting
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
 * Will provide basic formatting for same needed conversions of strings in special ways
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class Formatting
{

    /**
     * Will break up any path into a canonical form like realpath(), but does not require the file to exist.
     *
     * @param string $path The path to normalize
     *
     * @return mixed
     */
    public function normalizePath($path)
    {
        return array_reduce(
            explode('/', $path),
            function ($a, $b) {
                if ($a === 0) {
                    $a = "/";
                }

                if ($b === "") {
                    return $a;
                }

                if ($b === ".") {
                    return str_replace(DIRECTORY_SEPARATOR . "Utils", "", __DIR__);
                }

                if ($b === "..") {
                    return dirname($a);
                }

                return preg_replace("/\/+/", "/", "$a/$b");
            }
        );
    }

    /**
     * Will turn any wrongly used directory separator in the OS specific one
     *
     * @param string $path      The path to sanitize
     * @param string $separator The separator to sanitize with, DIRECTORY_SEPARATOR by default
     *
     * @return string
     */
    public function sanitizeSeparators($path, $separator = DIRECTORY_SEPARATOR)
    {
        return str_replace(array('/', '\\'), $separator, $path);
    }

    /**
     * Converts a string by escaping all regex relevant characters in it.
     *
     * @param string $string The string to convert
     *
     * @return string|array
     */
    public function toRegex($string)
    {
        return str_replace(
            array('$', '(', ')', '*', '[', ']', ' ', '/'),
            array('\$', '\(', '\)', '\*', '\[', '\]', '\s*', '\/'),
            $string
        );
    }
}
