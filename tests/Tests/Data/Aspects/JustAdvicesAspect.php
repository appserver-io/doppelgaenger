<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\Aspects\JustAdvicesAspect
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

/**
 * This aspect only contains advice and no pointcuts. It can be used to test cross references in between aspects and
 * provides advices to be used as interceptors
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 *
 * @Aspect
 */
class JustAdvicesAspect
{

    /**
     * Empty dummy "Before" advice
     *
     * @param \AppserverIo\Doppelgaenger\Interfaces\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     *
     * @Before
     */
    public function basicBeforeAdvice(MethodInvocationInterface $methodInvocation)
    {

    }

    /**
     * Empty dummy "After" advice
     *
     * @param \AppserverIo\Doppelgaenger\Interfaces\MethodInvocationInterface $methodInvocation Initially invoked method
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
     * @param \AppserverIo\Doppelgaenger\Interfaces\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     *
     * @AfterThrowing
     */
    public function basicAfterThrowingAdvice(MethodInvocationInterface $methodInvocation)
    {

    }

    /**
     * Empty dummy "AfterReturning" advice
     *
     * @param \AppserverIo\Doppelgaenger\Interfaces\MethodInvocationInterface $methodInvocation Initially invoked method
     *
     * @return null
     *
     * @AfterReturning
     */
    public function basicAfterReturningAdvice(MethodInvocationInterface $methodInvocation)
    {

    }

    /**
     * Empty dummy "Around" advice
     *
     * @param \AppserverIo\Doppelgaenger\Interfaces\MethodInvocationInterface $methodInvocation Initially invoked method
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
