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

namespace AppserverIo\Doppelgaenger\Tests\Data\Aspects;

use AppserverIo\Doppelgaenger\Entities\MethodInvocation;

/**
 * AppserverIo\Doppelgaenger\Tests\Data\Aspects\MainAspectTestClass
 *
 * Test class which provides some advices which can be weaved into test code
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 *
 * @Aspect
 */
class MainAspectTestClass
{

    /**
     * @Pointcut("call(\AppserverIo\Doppelgaenger\Tests\Data\Advised\AdvisedTestClass->publicSimpleMethod())")
     */
    public function booleanAdvisedMethods()
    {}

    /**
     * Advice used to proceed a method but always replace the result with true
     *
     * @param \AppserverIo\Doppelgaenger\Entities\MethodInvocation $methodInvocation Initially invoked method
     *
     * @return boolean
     *
     * @Around("pointcut(booleanAdvisedMethods())")
     */
    public static function trueAdvice1(MethodInvocation $methodInvocation)
    {
        $methodInvocation->proceed();
        return true;
    }

    /**
     * @Pointcut("call(\AppserverIo\Doppelgaenger\Tests\Data\Advised\AdvisedRegexClas*->regexClassMethod())")
     */
    public function regexAdvisedClass()
    {}

    /**
     * Advice used to proceed a method
     *
     * @param \AppserverIo\Doppelgaenger\Entities\MethodInvocation $methodInvocation Initially invoked method
     *
     * @return mixed
     *
     * @Around("pointcut(regexAdvisedClass())")
     */
    public static function lazyAroundAdvice(MethodInvocation $methodInvocation)
    {
        return $methodInvocation->proceed();
    }

    /**
     * @Pointcut("if(true) && call(\AppserverIo\Doppelgaenger\Tests\Data\Advised\AdvisedRegexClass->regexMethodMeth*())")
     */
    public function regexAdvisedMethod()
    {}

    /**
     * Advice used to proceed a method but always replace the result with false
     *
     * @param \AppserverIo\Doppelgaenger\Entities\MethodInvocation $methodInvocation Initially invoked method
     *
     * @return mixed
     *
     * @Around("pointcut(regexAdvisedMethod())")
     */
    public static function falseAdvice(MethodInvocation $methodInvocation)
    {
        $methodInvocation->proceed();
        return false;
    }

    /**
     * @Pointcut("call(\AppserverIo\Doppelgaenger\Tests\Data\Advised\AdvisedTestClass->aroundChainMethod())")
     */
    public function aroundChainMethod()
    {}

    /**
     * Advice to test around advice method chaining
     *
     * @param \AppserverIo\Doppelgaenger\Entities\MethodInvocation $methodInvocation Initially invoked method
     *
     * @return mixed
     *
     * @Around("pointcut(aroundChainMethod())")
     */
    public static function chainingAdvice1(MethodInvocation $methodInvocation)
    {
        $tmp = $methodInvocation->proceed();
        $tmp[] = __FUNCTION__;
        return $tmp;
    }

    /**
     * Advice to test around advice method chaining
     *
     * @param \AppserverIo\Doppelgaenger\Entities\MethodInvocation $methodInvocation Initially invoked method
     *
     * @return mixed
     *
     * @Around("pointcut(aroundChainMethod())")
     */
    public static function chainingAdvice2(MethodInvocation $methodInvocation)
    {
        $tmp = $methodInvocation->proceed();
        $tmp[] = __FUNCTION__;
        return $tmp;
    }

    /**
     * @Pointcut("call(\AppserverIo\Doppelgaenger\Tests\Data\Advised\AdvisedTestClass->falseMethod1())")
     */
    public function trueAdvisedMethod1()
    {}

    /**
     * @Pointcut("call(\AppserverIo\Doppelgaenger\Tests\Data\Advised\AdvisedTestClass->falseMethod2())")
     */
    public function trueAdvisedMethod2()
    {}

    /**
     * Advice used to proceed a method but always replace the result with true
     *
     * @param \AppserverIo\Doppelgaenger\Entities\MethodInvocation $methodInvocation Initially invoked method
     *
     * @return boolean
     *
     * @Around("pointcut(trueAdvisedMethod1())")
     */
    public static function trueAdvice(MethodInvocation $methodInvocation)
    {
        $methodInvocation->proceed();
        return true;
    }
}
