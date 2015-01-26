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
     *
     * @expectedException \AppserverIo\Doppelgaenger\Exceptions\MissingPropertyException
     */
    public function testMissingPropertyRead()
    {
        $test = $this->propertyTestClass->notExistingProperty;
    }

    /**
     * Check if we get a MissingPropertyException
     *
     * @return null
     *
     * @expectedException \AppserverIo\Doppelgaenger\Exceptions\MissingPropertyException
     */
    public function testMissingPropertyWrite()
    {
        $this->propertyTestClass->notExistingProperty = 'test';
    }

    /**
     * Check if we get an InvalidArgumentException
     *
     * @return null
     *
     * @expectedException \InvalidArgumentException
     */
    public function testPrivatePropertyWrite()
    {
        $this->propertyTestClass->privateNonCheckedProperty = 'test';
    }

    /**
     * Check if we get an InvalidArgumentException
     *
     * @return null
     *
     * @expectedException \InvalidArgumentException
     */
    public function testPrivatePropertyRead()
    {
        $test = $this->propertyTestClass->privateNonCheckedProperty;
    }

    /**
     * Check if we get an InvalidArgumentException
     *
     * @return null
     *
     * @expectedException \InvalidArgumentException
     */
    public function testPrivateCheckedPropertyWrite()
    {
        $this->propertyTestClass->privateCheckedProperty = 'test';
    }

    /**
     * Check if we get an InvalidArgumentException
     *
     * @return null
     *
     * @expectedException \InvalidArgumentException
     */
    public function testPrivateCheckedPropertyRead()
    {
        $test = $this->propertyTestClass->privateCheckedProperty;
    }

    /**
     * Check if we get any Exception
     *
     * @return null
     */
    public function testPublicPropertyWrite()
    {
        $this->propertyTestClass->publicNonCheckedProperty = 'test';
    }

    /**
     * Check if we get any Exception
     *
     * @return null
     */
    public function testPublicPropertyRead()
    {
        $test = $this->propertyTestClass->publicNonCheckedProperty;
    }

    /**
     * Check if we get any Exception
     *
     * @return null
     */
    public function testPublicQueckedProperty()
    {
        $this->propertyTestClass->publicCheckedProperty = 27.42;
        $test = $this->propertyTestClass->publicCheckedProperty;

        $this->assertEquals($test, 27.42);
    }
}
