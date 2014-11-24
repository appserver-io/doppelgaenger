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

namespace AppserverIo\Doppelgaenger\Tests\Functional;

use AppserverIo\Doppelgaenger\Tests\Data\ChildTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\BasicChildTestClass;

/**
 * AppserverIo\Doppelgaenger\Tests\Functional\InheritanceTest
 *
 * This test covers issues with inheritance of contracts
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class InheritanceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Will test if inheritance of precondition works over one level (class to class)
     *
     * @return null
     */
    public function testInheritance()
    {
        $testClass = new ChildTestClass();


        // These should fail
        $e = null;
        try {

            $testClass->pop();

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertInstanceOf("AppserverIo\\Doppelgaenger\\Exceptions\\BrokenPreconditionException", $e);

    }

    /**
     * Will test if inheritance works with overwritten methods having a different signature as the parent methods
     *
     * @return null
     */
    public function testChangedSignature()
    {
        $level = error_reporting();
        error_reporting(0);

        $testClass = new BasicChildTestClass();

        // Reset the error reporting level to the original value
        error_reporting($level);
        // These should not fail
        $e = null;
        try {

            $testClass->concatSomeStuff(12, 'test');

        } catch (\Exception $e) {
        }

        // Did we get null?
        $this->assertNull($e);

        // These should not fail as well
        $e = null;
        try {

            $testClass->stringToArray('this is a ', 'test');

        } catch (\Exception $e) {
        }

        // Did we get null?
        $this->assertNull($e);
    }
}
