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

use AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface;
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
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
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
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
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
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
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
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
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
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
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

    /**
     * @Pointcut("call(\AppserverIo\Doppelgaenger\Tests\Data\Advised\PointcutReferencingTestClass->iHaveTwoAroundAdvicesIncrementingMyResult())")
     */
    public function PointcutReferencingIHaveTwoAroundAdvicesIncrementingMyResult()
    {}

    /**
     * Basic Around advice incrementing the result of the wrapped method
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return integer
     *
     * @Around("pointcut(PointcutReferencingIHaveTwoAroundAdvicesIncrementingMyResult)")
     */
    public static function incrementResultAdvice(MethodInvocationInterface $methodInvocation)
    {
        $result = (int) $methodInvocation->proceed();
        $result++;
        return $result;
    }

    /**
     * @Pointcut("call(\AppserverIo\Doppelgaenger\Tests\Data\Advised\PointcutReferencingTestClass->iHaveTwoBeforeAdvices())")
     */
    public function PointcutReferencingIHaveTwoBeforeAdvicesIncrementingTheParam()
    {}

    /**
     * Basic Before advice incrementing the param of the wrapped method
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return void
     *
     * @Before("pointcut(PointcutReferencingIHaveTwoBeforeAdvicesIncrementingTheParam)")
     */
    public static function paramIncrementingAdvice(MethodInvocationInterface $methodInvocation)
    {
        $param = $methodInvocation->getParameters()['param'];
        $param++;
        $methodInvocation->setParameters(array('param' => $param));
    }

    /**
     * @Pointcut("call(\AppserverIo\Doppelgaenger\Tests\Data\Advised\PointcutReferencingTestClass->iHaveTwoBeforeAdvicesOfTheSameAspect())")
     */
    public function PointcutReferencingIHaveTwoBeforeAdvicesOfTheSameAspect()
    {}

    /**
     * Basic Before advice incrementing the param of the wrapped method
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return void
     *
     * @Before("pointcut(PointcutReferencingIHaveTwoBeforeAdvicesOfTheSameAspect)")
     */
    public static function paramIncrementingAdvice1(MethodInvocationInterface $methodInvocation)
    {
        $param = $methodInvocation->getParameters()['param'];
        $param++;
        $methodInvocation->setParameters(array('param' => $param));
    }

    /**
     * Basic Before advice incrementing the param of the wrapped method
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return void
     *
     * @Before("pointcut(PointcutReferencingIHaveTwoBeforeAdvicesOfTheSameAspect)")
     */
    public static function paramIncrementingAdvice2(MethodInvocationInterface $methodInvocation)
    {
        $param = $methodInvocation->getParameters()['param'];
        $param++;
        $methodInvocation->setParameters(array('param' => $param));
    }

    /**
     * @Pointcut("call(\AppserverIo\Doppelgaenger\Tests\Data\Advised\PointcutReferencingTestClass->iHaveASimpleBeforeAdvice1())")
     */
    public function PointcutReferencingIHaveASimpleBeforeAdvice1()
    {}

    /**
     * @Pointcut("call(\AppserverIo\Doppelgaenger\Tests\Data\Advised\PointcutReferencingTestClass->iHaveASimpleBeforeAdvice2())")
     */
    public function PointcutReferencingIHaveASimpleBeforeAdvice2()
    {}

    /**
     * Basic Before advice incrementing the param of the wrapped methods.
     * Will reference two pointcuts
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return void
     *
     * @Before("pointcut(PointcutReferencingIHaveASimpleBeforeAdvice1, PointcutReferencingIHaveASimpleBeforeAdvice2)")
     */
    public static function paramIncrementingSeveralPointcutsAdvice(MethodInvocationInterface $methodInvocation)
    {
        $param = $methodInvocation->getParameters()['param'];
        $param++;
        $methodInvocation->setParameters(array('param' => $param));
    }

    /**
     * Pointcut which "or" combines two call based pointcuts which are both using wildcards
     *
     * @Pointcut("call(\AppserverIo\Doppelgaenger\Tests\Data\Advised\PointcutWildcardTestClass1->do*()) || call(\AppserverIo\Doppelgaenger\Tests\Data\Advised\PointcutWildcardTestClass2->do*())")
     */
    public function PointcutReferencingSeveralRegexedMethods()
    {}

    /**
     * Basic Around advice always returning TRUE. Used to test if methods got wrapped correctly
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return boolean
     *
     * @Around("pointcut(PointcutReferencingSeveralRegexedMethods)")
     */
    public static function severalRegexedMethodsAdvice(MethodInvocationInterface $methodInvocation)
    {
        $methodInvocation->proceed();
        return true;
    }
}
