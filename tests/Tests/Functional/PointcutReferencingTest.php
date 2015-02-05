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
use AppserverIo\Doppelgaenger\Tests\Data\Aspects\PointcutReferencingTestAspect;

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
     * Default constructor
     */
    public function __construct()
    {
        // pipe the aspect through the generator to make it known
        new PointcutReferencingTestAspect();
    }

    /**
     * Set up our test class
     *
     * @return null
     */
    public function setUp()
    {
        $this->testClass = new PointcutReferencingTestClass();
        PointcutReferencingTestClass::$staticStorage = null;
    }

    /**
     * Tests if a Before advice gets woven at its correct position
     *
     * @return null
     */
    public function testPointcutBeforeSelection()
    {
        $this->testClass->iHaveABeforeAdvice();
        $methodInvocation = PointcutReferencingTestClass::$staticStorage;

        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Interfaces\MethodInvocationInterface', $methodInvocation);
        $this->assertNull($methodInvocation->getResult());
        $this->assertNull($methodInvocation->getThrownException());
    }

    /**
     * Tests if a After advice gets woven at its correct position
     *
     * @return null
     *
     * @throws \Exception
     *
     * @expectedException \Exception
     */
    public function testPointcutAfterSelection()
    {
        $this->testClass->iHaveAnAfterAdviceAndReturnSomething();
        $methodInvocation = PointcutReferencingTestClass::$staticStorage;

        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Interfaces\MethodInvocationInterface', $methodInvocation);
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
     * @return null
     *
     * @throws \Exception
     *
     * @expectedException \Exception
     */
    public function testPointcutAfterReturningSelection()
    {
        $this->testClass->iHaveAnAfterReturningAdviceAndReturnSomething();
        $methodInvocation = PointcutReferencingTestClass::$staticStorage;

        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Interfaces\MethodInvocationInterface', $methodInvocation);
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
     * @return null
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

            $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Interfaces\MethodInvocationInterface', $methodInvocation);
            $this->assertNull($methodInvocation->getResult());
            $this->assertInstanceOf('\Exception', $methodInvocation->getThrownException());
            throw $e;
        }
    }
}
