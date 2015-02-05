<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\Aspects\PointcutReferencingTestAspect
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

use AppserverIo\Doppelgaenger\Interfaces\MethodInvocationInterface;
use AppserverIo\Doppelgaenger\Tests\Data\Advised\PointcutReferencingTestClass;

/**
 * Used to test if all advices will be woven where they should be, based on the given pointcut
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 *
 * @Aspect
 */
class PointcutReferencingTestAspect
{

    /**
     * @Pointcut("call(\AppserverIo\Doppelgaenger\Tests\Data\Advised\PointcutReferencingTestClass->iHaveABeforeAdvice())")
     */
    public function iHaveABeforeAdvice()
    {}

    /**
     * Empty dummy "Before" advice
     *
     * @param \AppserverIo\Doppelgaenger\Interfaces\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     *
     * @Before("pointcut(iHaveABeforeAdvice)")
     */
    public function basicBeforeAdvice(MethodInvocationInterface $methodInvocation)
    {
        PointcutReferencingTestClass::$staticStorage = clone $methodInvocation;
    }

    /**
     * @Pointcut("call(\AppserverIo\Doppelgaenger\Tests\Data\Advised\PointcutReferencingTestClass->iHaveAnAfterAdvice*)")
     */
    public function iHaveAnAfterAdvice()
    {}

    /**
     * Empty dummy "After" advice
     *
     * @param \AppserverIo\Doppelgaenger\Interfaces\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     *
     * @After("pointcut(iHaveAnAfterAdvice)")
     */
    public function basicAfterAdvice(MethodInvocationInterface $methodInvocation)
    {
        PointcutReferencingTestClass::$staticStorage = clone $methodInvocation;
    }

    /**
     * @Pointcut("call(\AppserverIo\Doppelgaenger\Tests\Data\Advised\PointcutReferencingTestClass->iHaveAnAfterReturningAdvice*)")
     */
    public function iHaveAnAfterReturningAdvice()
    {}

    /**
     * Empty dummy "AfterReturning" advice
     *
     * @param \AppserverIo\Doppelgaenger\Interfaces\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     *
     * @AfterReturning("pointcut(iHaveAnAfterReturningAdvice)")
     */
    public function basicAfterReturningAdvice(MethodInvocationInterface $methodInvocation)
    {
        PointcutReferencingTestClass::$staticStorage = clone $methodInvocation;
    }

    /**
     * @Pointcut("call(\AppserverIo\Doppelgaenger\Tests\Data\Advised\PointcutReferencingTestClass->iHaveAnAfterThrowingAdvice*)")
     */
    public function iHaveAnAfterThrowingAdvice()
    {}

    /**
     * Empty dummy "AfterThrowing" advice
     *
     * @param \AppserverIo\Doppelgaenger\Interfaces\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     *
     * @AfterThrowing("pointcut(iHaveAnAfterThrowingAdvice)")
     */
    public function basicAfterThrowingAdvice(MethodInvocationInterface $methodInvocation)
    {
        PointcutReferencingTestClass::$staticStorage = clone $methodInvocation;
    }

    /**
     * @Pointcut("call(\AppserverIo\Doppelgaenger\Tests\Data\Advised\PointcutReferencingTestClass->iHaveAnAroundAdvice())")
     */
    public function iHaveAnAroundAdvice()
    {}

    /**
     * Empty dummy "Around" advice
     *
     * @param \AppserverIo\Doppelgaenger\Interfaces\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     *
     * @Around("pointcut(iHaveAnAroundAdvice)")
     */
    public function basicAroundAdvice(MethodInvocationInterface $methodInvocation)
    {
        $methodInvocation->proceed();
        PointcutReferencingTestClass::$staticStorage = clone $methodInvocation;
    }
}
