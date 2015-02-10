<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\AroundAdviceTestClass
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

namespace AppserverIo\Doppelgaenger\Tests\Data;

use AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface;

/**
 * Class used to test the correct workflow of a proceeding or blocking around advice
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class AroundAdviceTestClass
{
    /**
     * Property used to count invocations and other things
     *
     * @var integer $counter
     */
    public static $counter;

    /**
     * Property used to test the state of the class instance
     *
     * @var mixed $testableState1
     */
    public static $testableState1;

    /**
     * Property used to test the state of the class instance
     *
     * @var mixed $testableState2
     */
    public static $testableState2;

    /**
     * Default constructor
     */
    public function __construct()
    {
        self::$counter = 0;
    }

    /**
     * Method which will be proceeded by advice
     *
     * @return null
     *
     * @Around("advise(AroundAdviceTestClass->proceedingAdvice)")
     */
    public function proceededAdvisedMethod()
    {
        self::$testableState2 = true;
    }

    /**
     * Advice used to proceed a method and set a property before
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     */
    public static function proceedingAdvice(MethodInvocationInterface $methodInvocation)
    {
        self::$testableState1 = true;

        return $methodInvocation->proceed();
    }

    /**
     * Method which will be blocked by advice
     *
     * @return null
     *
     * @Around("advise(AroundAdviceTestClass->blockingAdvice())")
     */
    public function blockedAdvisedMethod()
    {
        self::$testableState2 = true;
    }

    /**
     * Advice used to block a method and set a property before
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     */
    public static function blockingAdvice(MethodInvocationInterface $methodInvocation)
    {
        self::$testableState1 = true;
    }

    /**
     * Method which will be proceeded by advice while the invocation is counted
     *
     * @return null
     *
     * @Around("advise(AroundAdviceTestClass->countedAfterAdvice())")
     */
    public function countedAfterAdvisedMethod()
    {
        self::$counter ++;
        self::$testableState2 = self::$counter;
    }

    /**
     * Advice used to proceed a method and increase an invocation counter.
     * This is done to test if the advice code gets executed BEFORE proceeding
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     */
    public static function countedAfterAdvice(MethodInvocationInterface $methodInvocation)
    {
        self::$counter ++;
        self::$testableState1 = self::$counter;

        return $methodInvocation->proceed();
    }

    /**
     * Method which will be proceeded by advice while the invocation is counted
     *
     * @return null
     *
     * @Around("advise(AroundAdviceTestClass->countedBeforeAdvice)")
     */
    public function countedBeforeAdvisedMethod()
    {
        self::$counter ++;
        self::$testableState1 = self::$counter;
    }

    /**
     * Advice used to proceed a method and increase an invocation counter.
     * This is done to test if the advice code gets executed BEFORE proceeding
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Aop\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     */
    public static function countedBeforeAdvice(MethodInvocationInterface $methodInvocation)
    {
        $result = $methodInvocation->proceed();

        self::$counter ++;
        self::$testableState2 = self::$counter;

        return $result;
    }
}
