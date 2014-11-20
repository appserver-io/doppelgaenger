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
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 *
 * @Aspect
 */
class MainAspectTestClass
{

    /**
     * @Pointcut("call(\AppserverIo\Doppelgaenger\Tests\Data\AdvisedTestClass->publicSimpleMethod())")
     */
    public function booleanAdvisedMethods($param1)
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
    public static function booleanAdvice(MethodInvocation $methodInvocation)
    {
        $methodInvocation->proceed();
        return true;
    }
}
