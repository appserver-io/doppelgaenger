<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\Annotations\MethodInvocationTestClass
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

namespace AppserverIo\Doppelgaenger\Tests\Data\Annotations;

use AppserverIo\Doppelgaenger\Exceptions\BrokenInvariantException;
use AppserverIo\Doppelgaenger\Tests\Data\Aspects\MethodInvocationTestAspect;

/**
 * Class which implements several methods which are used to test the functionality of the method invocation object
 *
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class MethodInvocationTestClass
{

    /**
     * Property which can be used to temporarily store arbitrary data so transport the internal flow to our test classes
     *
     * @var mixed $testStorage
     */
    public $testStorage;

    /**
     * Property which can be used to temporarily store arbitrary data so transport the internal flow to our test classes
     *
     * @var mixed $staticStorage
     */
    public static $staticStorage;

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->testStorage = null;
    }

    /**
     * @Before("advise(MethodInvocationTestAspect->basicBeforeAdvice())")
     */
    public function iHaveABeforeAdvice($param1, $param2)
    {
        $this->testStorage = array($param1, $param2);
    }

    /**
     * @Before("advise(MethodInvocationTestAspect->parametersAlteringBeforeAdvice())")
     */
    public function iHaveAParameterAlteringBeforeAdvice($param1, $param2)
    {
        $this->testStorage = array($param1, $param2);
    }

    /**
     * @After("advise(MethodInvocationTestAspect->basicAfterAdvice())")
     */
    public function iHaveAnAfterAdvice()
    {

    }

    /**
     * We throw a very weird exception so we can test if we get the right instance in our advice
     *
     * @AfterThrowing("advise(MethodInvocationTestAspect->basicAfterThrowingAdvice())")
     */
    public function iHaveAnAfterThrowingAdvice()
    {
        throw new BrokenInvariantException(__FUNCTION__);
    }

    /**
     * @AfterThrowing("advise(MethodInvocationTestAspect->exceptionAlteringAfterThrowingAdvice())")
     */
    public function iHaveAnExceptionAlteringThrowingAdvice()
    {
        throw new BrokenInvariantException('iHaveAnExceptionAlteringThrowingAdvice');
    }

    /**
     * @AfterReturning("advise(MethodInvocationTestAspect->basicAfterReturningAdvice())")
     */
    public function iHaveAnAfterReturningAdvice()
    {

    }

    /**
     * @AfterReturning("advise(MethodInvocationTestAspect->neverReachedAfterReturningAdvice())")
     */
    public function iHaveAnAfterReturningAdviceWhichIsNotReached()
    {
        throw new \Exception();
    }

    /**
     * @AfterReturning("advise(MethodInvocationTestAspect->resultAlteringAfterReturning())")
     */
    public function iHaveAResultAlteringAfterReturningAdvice()
    {
        return 'iHaveAResultAlteringAfterReturningAdvice';
    }

    /**
     * @Around("advise(MethodInvocationTestAspect->basicAroundAdvice())")
     */
    public function iHaveAnAroundAdvice()
    {

    }
}
