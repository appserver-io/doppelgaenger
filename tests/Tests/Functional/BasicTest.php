<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Functional\BasicTest
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

namespace AppserverIo\Doppelgaenger\Tests\Functional;

use AppserverIo\Doppelgaenger\Tests\Data\BasicTestClass;

/**
 * This test is for basic problems like broken type safety or invariant support
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class BasicTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Will check if operations on invariant protected attributes will bring the intended result
     *
     * @return null
     */
    public function testInvariantBreaks()
    {
        // Get the object to test
        $test = new BasicTestClass();

        // This one should not break
        $test->iDontBreakTheInvariant();

        $e = null;
        try {
            $test->iBreakTheInvariant();

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertInstanceOf("AppserverIo\\Doppelgaenger\\Exceptions\\BrokenInvariantException", $e);
    }

    /**
     * Will test enforcement of type hinting
     *
     * @return null
     */
    public function testParamTyping()
    {
        // Get the object to test
        $test = new BasicTestClass();

        // These tests should all be successful
        $test->stringToArray("null");
        $test->concatSomeStuff(17, 'test', new \Exception());
        $test->stringToWelcome('stranger');


        // These should all fail
        $e = null;
        try {
            $test->stringToArray(13);

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertInstanceOf("AppserverIo\\Doppelgaenger\\Exceptions\\BrokenPreconditionException", $e);

        $e = null;
        try {
            $test->concatSomeStuff("26", array(), new \Exception());

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertInstanceOf("AppserverIo\\Doppelgaenger\\Exceptions\\BrokenPreconditionException", $e);

        $e = null;
        try {
            $test->stringToWelcome(34);

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertInstanceOf("AppserverIo\\Doppelgaenger\\Exceptions\\BrokenPreconditionException", $e);
    }
}
