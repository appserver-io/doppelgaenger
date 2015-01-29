<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\Annotations\MixedTestClass
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

/**
 * Test class containing directly annotated methods with several advices with different joinpoints each
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class MixedTestClass
{

    /**
     * @Before("weave(Logger->error($param1))")
     * @After("weave(Logger::error(__METHOD__))")
     */
    public function iHaveSeveralAdvices1($param1)
    {

    }

    /**
     * @After("weave(Logger->log(__METHOD__))")
     * @Around("advise(Test->testIt())")
     */
    public function iHaveSeveralAdvices2($param1)
    {

    }

    /**
     * @AfterReturning("weave(Logger->error(__FUNCTION__))")
     * @After("weave(Test->testIt())")
     */
    public function iHaveSeveralAdvices3($param1)
    {

    }
}
