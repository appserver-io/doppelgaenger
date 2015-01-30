<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\Annotations\SingleTestClass
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
use AppserverIo\Doppelgaenger\Tests\Data\Aspects\JustAdvicesAspect;

/**
 * Test class containing directly annotated methods with one advice each, covering all possible join-points
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class SingleTestClass
{

    /**
     * @Before("weave(Logger->error($param1))")
     */
    public function iHaveABeforeWeave($param1)
    {

    }

    /**
     * @After("weave(Logger->error($param1))")
     */
    public function iHaveAnAfterWeave($param1)
    {

    }

    /**
     * @AfterThrowing("weave(Logger->error($param1))")
     */
    public function iHaveAnAfterThrowingWeave($param1)
    {

    }

    /**
     * @AfterReturning("weave(Logger->error($param1))")
     */
    public function iHaveAnAfterReturningWeave($param1)
    {

    }

    /**
     * @Before("advise(JustAdvicesAspect->basicBeforeAdvice())")
     */
    public function iHaveABeforeAdvice($param1)
    {

    }

    /**
     * @After("advise(JustAdvicesAspect->basicAfterAdvice())")
     */
    public function iHaveAnAfterAdvice($param1)
    {

    }

    /**
     * We throw a very weird exception so we can test if we get the right instance in our advice
     *
     * @AfterThrowing("advise(JustAdvicesAspect->basicAfterThrowingAdvice())")
     */
    public function iHaveAnAfterThrowingAdvice($param1)
    {
        throw new BrokenInvariantException(__FUNCTION__);
    }

    /**
     * @AfterReturning("advise(JustAdvicesAspect->basicAfterReturningAdvice())")
     */
    public function iHaveAnAfterReturningAdvice($param1)
    {

    }
}
