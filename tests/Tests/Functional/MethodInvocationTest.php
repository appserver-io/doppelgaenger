<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Functional\MethodInvocationTest
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

use AppserverIo\Doppelgaenger\Tests\Data\Annotations\MethodInvocationTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\Aspects\MethodInvocationTestAspect;

/**
 * Tests if the method invocation object is available, contains what it should and behaves like intended
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class MethodInvocationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The advised test class instance we can use to test our assumptions
     *
     * @var \AppserverIo\Doppelgaenger\Tests\Data\Annotations\MethodInvocationTestClass $testClass
     */
    protected $testClass;

    /**
     * Default constructor
     */
    public function __construct()
    {
        // pipe the aspect through the generator to make it known
        $aspect = new MethodInvocationTestAspect();
    }

    /**
     * Set up our test class
     *
     * @return null
     */
    public function setUp()
    {
        $this->testClass = new MethodInvocationTestClass();
        MethodInvocationTestClass::$staticStorage = null;
    }

    /**
     * Tests if parameters of the original call can be transported through our advice without any harm
     *
     * @return null
     */
    public function testBeforeSameParameterReceived()
    {
        $this->testClass->iHaveABeforeAdvice('stuff', new \Exception());

        $this->assertEquals('stuff', $this->testClass->testStorage[0]);
        $this->assertEquals(new \Exception(), $this->testClass->testStorage[1]);
    }

    /**
     * Tests if we are able to manipulate the function parameters if we want to
     *
     * @return null
     */
    public function testBeforeParametersCanBeChanged()
    {
        $this->testClass->iHaveAParameterAlteringBeforeAdvice('stuff', new \stdClass());

        $this->assertEquals(new \stdClass(), $this->testClass->testStorage[0]);
        $this->assertEquals('parametersAlteringBeforeAdvice', $this->testClass->testStorage[1]);
    }

    /**
     * Tests if we can weave an After advice
     *
     * @return null
     */
    public function testAfter()
    {
        $this->testClass->iHaveAnAfterAdvice();
    }

    /**
     * Tests if the after throwing advice can catch the exception
     *
     * @return null
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenInvariantException
     */
    public function testAfterThrowingCorrectExceptionInstance()
    {
        $this->testClass->iHaveAnAfterThrowingAdvice();
    }

    /**
     * Tests if the caught exception is immutable by our advice
     *
     * @return null
     *
     * @throws \Exception
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenInvariantException
     */
    public function testAfterThrowingExceptionIsImmutable()
    {
        try {
            $this->testClass->iHaveAnExceptionAlteringThrowingAdvice();

        } catch (\Exception $e) {

            $this->assertEquals('iHaveAnExceptionAlteringThrowingAdvice', $e->getMessage());
            throw $e;
        }
    }

    /**
     * Tests if we can weave an AfterReturning advice
     *
     * @return null
     */
    public function testAfterReturning()
    {
        $this->testClass->iHaveAnAfterReturningAdvice();
    }

    /**
     * Tests if parameters of the original call can be transported through our advice without any harm
     *
     * @return null
     */
    public function testAfterReturningSameResultReceived()
    {
        $this->testClass->iHaveABeforeAdvice('stuff', new \Exception());

        $this->assertEquals('stuff', $this->testClass->testStorage[0]);
        $this->assertEquals(new \Exception(), $this->testClass->testStorage[1]);
    }

    /**
     * Tests if we are able to manipulate the function parameters if we want to
     *
     * @return null
     */
    public function testAfterReturningResultIsImmutable()
    {
        $result = $this->testClass->iHaveAResultAlteringAfterReturningAdvice();

        $this->assertEquals('iHaveAResultAlteringAfterReturningAdvice', $result);
    }

    /**
     * Tests if an AfterReturning advice is never reached if an exception occurs
     *
     * @return null
     *
     * @expectedException \Exception
     */
    public function testAfterReturningWhichShouldNeverBeReached()
    {
        $this->testClass->iHaveAnAfterReturningAdviceWhichIsNotReached();
        $this->assertNull(MethodInvocationTestClass::$staticStorage);
    }

    /**
     * Tests if we can weave an Around advice
     *
     * @return null
     */
    public function testAround()
    {
        $this->testClass->iHaveAnAroundAdvice();
    }
}
