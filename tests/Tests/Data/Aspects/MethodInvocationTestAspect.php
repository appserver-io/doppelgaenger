<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\Aspects\MethodInvocationTestAspect
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

namespace AppserverIo\Doppelgaenger\Tests\Data\Aspects;

use AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface;
use AppserverIo\Doppelgaenger\Tests\Data\Annotations\MethodInvocationTestClass;

/**
 * Aspect used to test the usage of the MethodInvocation object
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 *
 * @Aspect
 */
class MethodInvocationTestAspect
{
    /**
     * Empty dummy "Before" advice
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     *
     * @Before
     */
    public function basicBeforeAdvice(MethodInvocationInterface $methodInvocation)
    {

    }

    /**
     * "Before" advice which tries to alter the passed parameters
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     *
     * @Before
     */
    public function parametersAlteringBeforeAdvice(MethodInvocationInterface $methodInvocation)
    {
        $methodInvocation->setParameters(array(new \stdClass(), 'parametersAlteringBeforeAdvice'));
    }

    /**
     * Empty dummy "After" advice
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     *
     * @After
     */
    public function basicAfterAdvice(MethodInvocationInterface $methodInvocation)
    {

    }

    /**
     * Empty dummy "AfterThrowing" advice
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     *
     * @AfterThrowing
     */
    public function basicAfterThrowingAdvice(MethodInvocationInterface $methodInvocation)
    {

    }

    /**
     * "AfterThrowing" advice which tries to alter the thrown exception
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     *
     * @AfterThrowing
     */
    public function exceptionAlteringAfterThrowingAdvice(MethodInvocationInterface $methodInvocation)
    {
        // this goes without saying but our implementation should not allow to alter the exception in any way
        $methodInvocation->injectThrownException(new \Exception('Haha!'));
        $exception = $methodInvocation->getThrownException();
        $exception = new \Exception('Haha!');
    }

    /**
     * Empty dummy "AfterReturning" advice
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     *
     * @AfterReturning
     */
    public function basicAfterReturningAdvice(MethodInvocationInterface $methodInvocation)
    {

    }

    /**
     * "AfterReturning" advice which should never be reached
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     *
     * @AfterReturning
     */
    public function neverReachedAfterReturningAdvice(MethodInvocationInterface $methodInvocation)
    {
        MethodInvocationTestClass::$staticStorage = 'I have been reached';
    }

    /**
     * "AfterReturning" advice which tries to alter the result
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     *
     * @AfterReturning
     */
    public function resultAlteringAfterReturning(MethodInvocationInterface $methodInvocation)
    {
        // this goes without saying but our implementation should not allow to alter the exception in any way
        $methodInvocation->injectResult(new \Exception('Haha!'));
        $result = $methodInvocation->getResult();
        $result = new \Exception('Haha!');
    }

    /**
     * Empty dummy "Around" advice
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     *
     * @Around
     */
    public function basicAroundAdvice(MethodInvocationInterface $methodInvocation)
    {
        $methodInvocation->proceed();
    }
}

