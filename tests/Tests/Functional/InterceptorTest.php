<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Functional\InterceptorTest
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

use AppserverIo\Doppelgaenger\Tests\Data\Annotations\SingleTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\Aspects\MethodInvocationAspect;

/**
 * Tests directly annotated, aka intercepted, method invocations
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class InterceptorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Default constructor
     */
    public function __construct()
    {
        // pipe the aspect through the generator to make it known
        $aspect = new MethodInvocationAspect();
    }

    /**
     * Test if a single and directly annotated pointcut works (around advice used)
     *
     * @return null
     *
     * @expectedException \AppserverIo\Doppelgaenger\Exceptions\BrokenInvariantException
     */
    public function testAfterThrowingCorrectExceptionInstance()
    {
        $class = new SingleTestClass();
        $class->iHaveAnAfterThrowingAdvice('stuff');
    }
}
