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
 * AppserverIo\Doppelgaenger\Tests\Data\Annotations\ComplexTestClass
 *
 * Test class containing directly annotated methods with complex advices where joinpoints might differ
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class ComplexTestClass
{

    /**
     * @Before("if($param1 === 1) && weave(Logger->error($param1))")
     */
    public function iHaveSeveralAdvices1($param1)
    {

    }

    /**
     * @After("(if($param1 === 'caller') || execute(Helper->caller())) && weave(Logger->log(__METHOD__))")
     * @AfterThrowing("if($param1 > 1) && if($param1 < 5) && weave(Test->testIt())")
     */
    public function iHaveSeveralAdvices2($param1)
    {

    }

    /**
     * @Before("(if($param1===1) || if($param1===2)) && weave(Logger->error(__METHOD__))")
     * @After("(if($param1===1) || (if($param1 > 2) && if($param1 < 5))) && weave(Logger->error(__METHOD__))")
     * @AfterThrowing("weave(Test->testIt()) && if($param1 !== false)")
     */
    public function iHaveSeveralAdvices3($param1)
    {

    }
}
