<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Functional\GeneratorTest
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

use AppserverIo\Doppelgaenger\Tests\Data\AroundAdviceTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\GeneratorTest\CustomProcessingTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\GeneratorTest\LocalCustomProcessingTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\TagPlacementTestClass;

/**
 * This test covers known generator problems
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class GeneratorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Will test if a randomly placed php tag will throw of the generator
     *
     * @return null
     */
    public function testPhpTag()
    {
        new TagPlacementTestClass();
    }

    /**
     * Method to test if an around advice is able to proceed the initially called method
     *
     * @return null
     */
    public function testProceededMethod()
    {
        // get a class instance and prepare it
        $testClass = new AroundAdviceTestClass();
        AroundAdviceTestClass::$testableState1 = false;
        AroundAdviceTestClass::$testableState2 = false;

        // call the processed method and check if both properties got changed
        $testClass->proceededAdvisedMethod();
        $this->assertTrue(AroundAdviceTestClass::$testableState1);
        $this->assertTrue(AroundAdviceTestClass::$testableState2);
    }

    /**
     * Method to test if an around advice is able to block the initially called method
     *
     * @return null
     */
    public function testBlockedMethod()
    {
        // get a class instance and prepare it
        $testClass = new AroundAdviceTestClass();
        AroundAdviceTestClass::$testableState1 = false;
        AroundAdviceTestClass::$testableState2 = false;

        // call the processed method and check if both properties got changed
        $testClass->blockedAdvisedMethod();
        $this->assertTrue(AroundAdviceTestClass::$testableState1);
        $this->assertFalse(AroundAdviceTestClass::$testableState2);
    }

    /**
     * Method to test if an around advice can proceed the advised method AFTER the own logic
     *
     * @return null
     */
    public function testAdviceAfterAdvisedOrder()
    {
        // get a class instance and prepare it
        $testClass = new AroundAdviceTestClass();
        AroundAdviceTestClass::$testableState1 = 0;
        AroundAdviceTestClass::$testableState2 = 0;

        // call the after-counted advised method and check if it got executed AFTER the advice
        $testClass->countedAfterAdvisedMethod();
        $this->assertSame(1, AroundAdviceTestClass::$testableState1);
        $this->assertSame(2, AroundAdviceTestClass::$testableState2);
    }

    /**
     * Method to test if an around advice can proceed the advised method BEFORE the own logic
     *
     * @return null
     */
    public function testAdviceBeforeAdvisedOrder()
    {
        // get a class instance and prepare it
        $testClass = new AroundAdviceTestClass();
        AroundAdviceTestClass::$testableState1 = 0;
        AroundAdviceTestClass::$testableState2 = 0;

        // call the before-counted advised method and check if it got executed BEFORE the advice
        $testClass->countedBeforeAdvisedMethod();
        $this->assertSame(1, AroundAdviceTestClass::$testableState1);
        $this->assertSame(2, AroundAdviceTestClass::$testableState2);
    }

    /**
     * Will test if we can enable custom enforcement processing on class level using the @Processing annotation
     *
     * @return null
     *
     * @expectedException \AppserverIo\Doppelgaenger\Tests\TestLoggerUsedException
     */
    public function testCustomClassProcessing()
    {
        $testCase = new CustomProcessingTestClass();
        $testCase->iHaveNoCustomProcessing();
    }

    /**
     * Will test if we can enable custom enforcement processing on method level using the @Processing annotation
     *
     * @return null
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPostconditionException
     */
    public function testCustomMethodProcessing()
    {
        $testCase = new CustomProcessingTestClass();
        $testCase->iHaveACustomExceptionProcessing();
    }

    /**
     * Will test if we can enable custom enforcement processing on method level using the @Processing annotation
     * without any annotation within the class doc block
     *
     * @return null
     *
     * @expectedException \AppserverIo\Doppelgaenger\Tests\TestLoggerUsedException
     */
    public function testCustomMethodOnlyProcessing1()
    {
        $testCase = new LocalCustomProcessingTestClass();
        $testCase->iHaveACustomLoggingProcessing();
    }

    /**
     * Will test if we can enable custom enforcement processing on method level using the @Processing annotation
     * without any annotation within the class doc block
     *
     * @return null
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPostconditionException
     */
    public function testCustomMethodOnlyProcessing2()
    {
        $testCase = new LocalCustomProcessingTestClass();
        $testCase->iHaveACustomExceptionProcessing();
    }
}
