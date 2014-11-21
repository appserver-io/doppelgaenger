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

namespace AppserverIo\Doppelgaenger\Tests\Data\Annotations;

/**
 * AppserverIo\Doppelgaenger\Tests\Data\Annotations\SingleTestClass
 *
 * Test class containing directly annotated methods with one advice each, covering all possible joinpoints
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class SingleTestClass
{

    /**
     * @Before("weave(Logger->error($param1))")
     */
    public function iHaveABeforeAdvice($param1)
    {

    }

    /**
     * @After("weave(Logger->error($param1))")
     */
    public function iHaveAnAfterAdvice($param1)
    {

    }

    /**
     * @AfterThrowing("weave(Logger->error($param1))")
     */
    public function iHaveAnAfterThrowingAdvice($param1)
    {

    }

    /**
     * @AfterReturning("weave(Logger->error($param1))")
     */
    public function iHaveAnAfterReturningAdvice($param1)
    {

    }
}
