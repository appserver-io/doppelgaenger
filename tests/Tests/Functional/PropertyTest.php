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

use AppserverIo\Doppelgaenger\Tests\Data\PropertyTestClass;

/**
 * AppserverIo\Doppelgaenger\Tests\Functional\PropertyTest
 *
 * Will test the invariant enforced attribute access
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class PropertyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \AppserverIo\Doppelgaenger\Tests\Data\PropertyTestClass $propertyTestClass Our test class
     */
    private $propertyTestClass;

    /**
     * We need the test class from the beginning
     */
    public function __construct()
    {
        $this->propertyTestClass = new PropertyTestClass();
    }

    /**
     * Check if we get a MissingPropertyException
     *
     * @return null
     */
    public function testMissingProperty()
    {
        $e = null;
        try {

            $this->propertyTestClass->notExistingProperty = 'test';

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertInstanceOf("AppserverIo\\Doppelgaenger\\Exceptions\\MissingPropertyException", $e);

        $e = null;
        try {

            $test = $this->propertyTestClass->notExistingProperty;

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertInstanceOf("AppserverIo\\Doppelgaenger\\Exceptions\\MissingPropertyException", $e);
    }

    /**
     * Check if we get an InvalidArgumentException
     *
     * @return null
     */
    public function testPrivateProperty()
    {
        $e = null;
        try {

            $this->propertyTestClass->privateNonCheckedProperty = 'test';

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertInstanceOf("\\InvalidArgumentException", $e);

        $e = null;
        try {

            $test = $this->propertyTestClass->privateNonCheckedProperty;

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertInstanceOf("\\InvalidArgumentException", $e);

        $e = null;
        try {

            $this->propertyTestClass->privateCheckedProperty = 'test';

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertInstanceOf("\\InvalidArgumentException", $e);

        $e = null;
        try {

            $test = $this->propertyTestClass->privateCheckedProperty;

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertInstanceOf("\\InvalidArgumentException", $e);
    }

    /**
     * Check if we get any Exception
     *
     * @return null
     */
    public function testPublicProperty()
    {
        $e = null;
        try {

            $this->propertyTestClass->publicNonCheckedProperty = 'test';

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertNull($e);

        $e = null;
        try {

            $test = $this->propertyTestClass->publicNonCheckedProperty;

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertNull($e);

        $e = null;
        try {

            $this->propertyTestClass->publicCheckedProperty = 27.42;

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertNull($e);

        $e = null;
        try {

            $test = $this->propertyTestClass->publicCheckedProperty;

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertNull($e);
        $this->assertEquals($test, 27.42);
    }
}
