<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\Advised\AbstractTestClass
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

namespace AppserverIo\Doppelgaenger\Tests\Data\Advised;

/**
 * Abstract test class, does nothing besides being an abstract target for AOP
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
abstract class AbstractTestClass
{

    /**
     * Will be targeted by pointcut based advices
     *
     * @return boolean
     */
    public function iHaveAPointcutBasedAdvice()
    {

    }

    /**
     * Will directly make use of our advices
     *
     * @return boolean
     *
     * @Around("advise(\AppserverIo\Doppelgaenger\Tests\Data\Aspects\MainAspectTestClass->basicAroundAdvice())")
     */
    public function iHaveADirectAdvice()
    {

    }
}
