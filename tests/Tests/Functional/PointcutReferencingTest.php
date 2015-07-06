<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Functional\PointcutReferencingTest
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

use AppserverIo\Doppelgaenger\Tests\Data\Advised\PointcutReferencingTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\Advised\PointcutWildcardTestClass1;
use AppserverIo\Doppelgaenger\Tests\Data\Advised\PointcutWildcardTestClass2;

/**
 * Test class which will test if we can reference the correct methods using pointcuts
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class PointcutReferencingTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The advised test class instance we can use to test our assumptions
     *
     * @var \AppserverIo\Doppelgaenger\Tests\Data\Advised\PointcutReferencingTestClass $testClass
     */
    protected $testClass;

    /**
     * Set up our test class
     *
     * @return void
     */
    public function setUp()
    {
        $this->testClass = new PointcutReferencingTestClass();
        PointcutReferencingTestClass::$staticStorage = null;
    }

    /**
     * Tests if a Before advice gets woven at its correct position
     *
     * @return void
     */
    public function testPointcutBeforeSelection()
    {
        $this->testClass->iHaveABeforeAdvice();
        $methodInvocation = PointcutReferencingTestClass::$staticStorage;

        $this->assertInstanceOf('\AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface', $methodInvocation);
        $this->assertNull($methodInvocation->getResult());
        $this->assertNull($methodInvocation->getThrownException());
    }

    /**
     * Tests if a After advice gets woven at its correct position
     *
     * @return void
     *
     * @throws \Exception
     *
     * @expectedException \Exception
     */
    public function testPointcutAfterSelection()
    {
        $this->testClass->iHaveAnAfterAdviceAndReturnSomething();
        $methodInvocation = PointcutReferencingTestClass::$staticStorage;

        $this->assertInstanceOf('\AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface', $methodInvocation);
        $this->assertEquals('iHaveAnAfterAdviceAndReturnSomething', $methodInvocation->getResult());
        $this->assertNull($methodInvocation->getThrownException());

        try {
            $this->testClass->iHaveAnAfterAdviceAndThrowSomething();

        } catch (\Exception $e) {
            $methodInvocation = PointcutReferencingTestClass::$staticStorage;

            $this->assertNull($methodInvocation->getResult());
            $this->assertInstanceOf('\Exception', $methodInvocation->getThrownException());

            throw $e;
        }
    }

    /**
     * Tests if a AfterReturning advice gets woven at its correct position
     *
     * @return void
     *
     * @throws \Exception
     *
     * @expectedException \Exception
     */
    public function testPointcutAfterReturningSelection()
    {
        $this->testClass->iHaveAnAfterReturningAdviceAndReturnSomething();
        $methodInvocation = PointcutReferencingTestClass::$staticStorage;

        $this->assertInstanceOf('\AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface', $methodInvocation);
        $this->assertEquals('iHaveAnAfterReturningAdviceAndReturnSomething', $methodInvocation->getResult());
        $this->assertNull($methodInvocation->getThrownException());

        // reset the static storage and test if we will get something again
        try {
            PointcutReferencingTestClass::$staticStorage = null;
            $this->testClass->iHaveAnAfterReturningAdviceAndThrowSomething();

        } catch (\Exception $e) {
            $this->assertNull(PointcutReferencingTestClass::$staticStorage);
            throw $e;
        }
    }

    /**
     * Tests if a AfterThrowing advice gets woven at its correct position
     *
     * @return void
     *
     * @throws \Exception
     *
     * @expectedException \Exception
     */
    public function testPointcutAfterThrowingSelection()
    {
        $this->testClass->iHaveAnAfterThrowingAdviceAndReturnSomething();
        $this->assertNull(PointcutReferencingTestClass::$staticStorage);

        // reset the static storage and test if we will get something again
        try {
            PointcutReferencingTestClass::$staticStorage = null;
            $this->testClass->iHaveAnAfterThrowingAdviceAndThrowSomething();

        } catch (\Exception $e) {
            $methodInvocation = PointcutReferencingTestClass::$staticStorage;

            $this->assertInstanceOf('\AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface', $methodInvocation);
            $this->assertNull($methodInvocation->getResult());
            $this->assertInstanceOf('\Exception', $methodInvocation->getThrownException());
            throw $e;
        }
    }

    /**
     * Tests if multiple around advices with the same name, but from different aspects can be used with one central pointcut
     *
     * @return void
     */
    public function testMultipleAroundAdvicesForOnePointcut()
    {
        $this->assertEquals(3, $this->testClass->iHaveTwoAroundAdvicesIncrementingMyResult());
    }

    /**
     * Tests if multiple around advices with the same name, but from different aspects can be used with one central pointcut
     *
     * @return void
     */
    public function testMultipleBeforeAdvicesForOnePointcut()
    {
        $result = $this->testClass->iHaveTwoBeforeAdvices(1);
        $this->assertEquals(3, $result);
    }

    /**
     * Tests if multiple around advices with the same name, but from different aspects can be used with one central pointcut
     *
     * @return void
     */
    public function testMultipleBeforeAdvicesOfSameAspectForOnePointcut()
    {
        $result = $this->testClass->iHaveTwoBeforeAdvicesOfTheSameAspect(1);
        $this->assertEquals(3, $result);
    }

    /**
     * Tests if multiple pointcuts can be referenced by one advice
     *
     * @return void
     */
    public function testMultiplePointcutsForOneBeforeAdvice()
    {
        $this->assertEquals(2, $this->testClass->iHaveASimpleBeforeAdvice1(1));
        $this->assertEquals(2, $this->testClass->iHaveASimpleBeforeAdvice2(1));
    }

    /**
     * Tests
     *
     * @return void
     */
    public function testPointcutWithSeveralRegexedClasses()
    {
        $testClass = new PointcutWildcardTestClass1();
        $this->assertTrue($testClass->doSomething());
        $this->assertTrue($testClass->doOtherThings());

        $testClass = new PointcutWildcardTestClass2();
        $this->assertTrue($testClass->doStuff());
        $this->assertTrue($testClass->doWhatever());
    }
}
