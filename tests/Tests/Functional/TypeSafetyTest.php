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

use AppserverIo\Doppelgaenger\Tests\Data\TypeSafetyTestClass;

/**
 * AppserverIo\Doppelgaenger\Tests\Functional\TypeSafetyTest
 *
 * Will test basic type safety
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class TypeSafetyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \AppserverIo\Doppelgaenger\Tests\Data\TypeSafetyTestClass $typeSafetyTestClass Our test class
     */
    private $typeSafetyTestClass;

    /**
     * Get our class
     */
    public function __construct()
    {
        $this->typeSafetyTestClass = new TypeSafetyTestClass();
    }

    /**
     * Check if we got enforced type safety for params
     *
     * @return null
     *
     * @expectedException \AppserverIo\Doppelgaenger\Exceptions\BrokenPreconditionException
     */
    public function testBasicPreconditionFail1()
    {
        $this->typeSafetyTestClass->iNeedStrings('stringer', 12);
    }

    /**
     * Check if we got enforced type safety for params
     *
     * @return null
     *
     * @expectedException \AppserverIo\Doppelgaenger\Exceptions\BrokenPreconditionException
     */
    public function testBasicPreconditionFail2()

    {
        $this->typeSafetyTestClass->iNeedArrays('test', array());
    }

    /**
     * Check if we got enforced type safety for params
     *
     * @return null
     *
     * @expectedException \AppserverIo\Doppelgaenger\Exceptions\BrokenPreconditionException
     */
    public function testBasicPreconditionFail3()
    {
        $this->typeSafetyTestClass->iNeedNumeric('four');
    }

    /**
     * Check if we got enforced type safety for params
     *
     * @return null
     */
    public function testBasicPreconditions()
    {

        $e = null;
        try {
            $this->typeSafetyTestClass->iNeedStrings('stringer', 'testinger');

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertNull($e);

        $e = null;
        try {
            $this->typeSafetyTestClass->iNeedArrays(array('test', 'test2'), array());

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertNull($e);

        $e = null;
        try {
            $this->typeSafetyTestClass->iNeedNumeric(12, 5);
            $this->typeSafetyTestClass->iNeedNumeric(42);

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertNull($e);
    }

    /**
     * Check if we got enforced type safety for return
     *
     * @return null
     */
    public function testBasicPostcondition()
    {
        $e = null;
        try {

            $this->typeSafetyTestClass->iReturnAString(12);

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertInstanceOf("AppserverIo\\Doppelgaenger\\Exceptions\\BrokenPostconditionException", $e);

        $e = null;
        try {

            $this->typeSafetyTestClass->iReturnAnArray('testinger');

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertInstanceOf("AppserverIo\\Doppelgaenger\\Exceptions\\BrokenPostconditionException", $e);

        $e = null;
        try {

            $this->typeSafetyTestClass->iReturnAnInt(array());

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertInstanceOf("AppserverIo\\Doppelgaenger\\Exceptions\\BrokenPostconditionException", $e);

        $e = null;
        try {

            $this->typeSafetyTestClass->iReturnAnArray();

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertNull($e);

        $e = null;
        try {

            $this->typeSafetyTestClass->iReturnAnInt();

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertNull($e);

        $e = null;
        try {

            $this->typeSafetyTestClass->iReturnAString();

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertNull($e);
    }
}
