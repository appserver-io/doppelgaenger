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
     * Instance of our test class
     *
     * @var \AppserverIo\Doppelgaenger\Tests\Data\BasicTestClass $testClass
     */
    protected $testClass;

    /**
     * Get our test class
     *
     * @return null
     */
    public function setUp()
    {
        $this->testClass = new BasicTestClass();
    }

    /**
     * Will check if operations on invariant protected attributes will bring the intended result
     *
     * @return null
     */
    public function testInvariantHolds()
    {
        // This one should not break
        $this->testClass->iDontBreakTheInvariant();
    }

    /**
     * Will check if operations on invariant protected attributes will bring the intended result
     *
     * @return null
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenInvariantException
     */
    public function testInvariantBreaks()
    {
        $this->testClass->iBreakTheInvariant();
    }

    /**
     * Will test enforcement of type hinting
     *
     * @return null
     */
    public function testParamTyping()
    {
        // These tests should all be successful
        $this->testClass->stringToArray("null");
        $this->testClass->concatSomeStuff(17, 'test', new \Exception());
        $this->testClass->stringToWelcome('stranger');
    }

    /**
     * Will test enforcement of type hinting
     *
     * @return null
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPreconditionException
     */
    public function testParamTypingSinglePrecondition1()
    {
        $this->testClass->stringToArray(13);
    }

    /**
     * Will test enforcement of type hinting
     *
     * @return null
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPreconditionException
     */
    public function testParamTypingSinglePrecondition2()
    {
        $this->testClass->stringToWelcome(34);
    }

    /**
     * Will test enforcement of type hinting
     *
     * @return null
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPreconditionException
     */
    public function testParamTypingMultiplePreconditions()
    {
        $this->testClass->concatSomeStuff("26", array(), new \Exception());
    }
}
