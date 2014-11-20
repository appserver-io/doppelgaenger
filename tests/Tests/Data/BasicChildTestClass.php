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
 * @subpackage Tests
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\Tests\Data;

/**
 * AppserverIo\Doppelgaenger\Tests\Data\BasicChildTestClass
 *
 * This class has the sole purpose of checking if overwritten methods with different signatures will be handled
 * correctly
 *
 * @category   Doppelgaenger
 * @package    AppserverIo\Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php
 *             Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
final class BasicChildTestClass extends BasicTestClass
{
    /**
     * @param integer $param17
     * @param string  $param2
     *
     * @return string
     */
    public function concatSomeStuff($param17, $param2)
    {
        return (string)$param17 . $param2;
    }

    /**
     * @param string $param1
     * @param string $param2
     *
     * @return array
     */
    public function stringToArray($param1, $param2)
    {
        return array($param1 . $param2);
    }
}
