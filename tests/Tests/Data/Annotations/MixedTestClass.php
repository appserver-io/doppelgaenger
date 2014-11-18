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
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Tests\Data\Annotations;

/**
 * AppserverIo\Doppelgaenger\Tests\Data\Annotations\MixedTestClass
 *
 * Test class containing directly annotated methods with several advices with different joinpoints each
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
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
