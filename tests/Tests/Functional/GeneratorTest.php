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
use AppserverIo\Doppelgaenger\Tests\Data\GeneratorTest\RecursionTestClass2;
use AppserverIo\Doppelgaenger\Tests\Data\GeneratorTest\BasicTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\GeneratorTest\MethodVariantionsTestClass;

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
     * @return void
     */
    public function testPhpTag()
    {
        new TagPlacementTestClass();
    }

    /**
     * Method to test if an around advice is able to proceed the initially called method
     *
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
     *
     * @expectedException \AppserverIo\Doppelgaenger\Tests\TestLoggerUsedException
     */
    public function testCustomClassProcessing()
    {
        $testClass = new CustomProcessingTestClass();
        $testClass->iHaveNoCustomProcessing();
    }

    /**
     * Will test if we can enable custom enforcement processing on method level using the @Processing annotation
     *
     * @return void
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPostconditionException
     */
    public function testCustomMethodProcessing()
    {
        $testClass = new CustomProcessingTestClass();
        $testClass->iHaveACustomExceptionProcessing();
    }

    /**
     * Will test if we can disable custom enforcement processing on method level using the @Processing annotation
     *
     * @return void
     */
    public function testCustomMethodNoneProcessing()
    {
        $testClass = new CustomProcessingTestClass();
        try {
            $testClass->iHaveNoProcessingAtAll();
        } catch (\Exception $e) {
            $this->fail('There should not be an exception at all');
        }
    }

    /**
     * Will test if we can enable custom enforcement processing on method level using the @Processing annotation
     * without any annotation within the class doc block
     *
     * @return void
     *
     * @expectedException \AppserverIo\Doppelgaenger\Tests\TestLoggerUsedException
     */
    public function testCustomMethodOnlyProcessing1()
    {
        $testClass = new LocalCustomProcessingTestClass();
        $testClass->iHaveACustomLoggingProcessing();
    }

    /**
     * Will test if we can enable custom enforcement processing on method level using the @Processing annotation
     * without any annotation within the class doc block
     *
     * @return void
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPostconditionException
     */
    public function testCustomMethodOnlyProcessing2()
    {
        $testClass = new LocalCustomProcessingTestClass();
        $testClass->iHaveACustomExceptionProcessing();
    }

    /**
     * Tests if we can catch potential endless recursions based on a call like parent::<METHOD_NAME>
     *
     * @return void
     */
    public function testEndlessRecursionSafety()
    {
        $testClass = new RecursionTestClass2();
        $testClass->iDontWantToBeRecursive();

        $this->assertEquals(1, $testClass->recursionCounter);
    }

    /**
     * Tests if we can generate proxies for all possible method definition headers e.g. final public statc function test()
     *
     * @return void
     */
    public function testMethodVariantFatalErrors()
    {
        $testClass = new MethodVariantionsTestClass();
    }
}
