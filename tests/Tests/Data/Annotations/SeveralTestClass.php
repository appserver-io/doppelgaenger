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
 * AppserverIo\Doppelgaenger\Tests\Data\Annotations\SeveralTestClass
 *
 * Test class containing directly annotated methods with several advices with the same joinpoint each
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class SeveralTestClass
{

    /**
     * @Before("weave(Logger->error($param1))")
     * @Before("weave(Logger::error(__METHOD__))")
     */
    public function iHaveSeveralBeforeAdvices($param1)
    {

    }

    /**
     * @After("weave(Logger->log(__METHOD__))")
     * @After("weave(Logger->log('hello world'))")
     * @After("weave(Test->testIt())")
     */
    public function iHaveSeveralAfterAdvices($param1)
    {

    }

    /**
     * @AfterReturning("weave(Logger->error(__FUNCTION__))")
     * @AfterReturning("weave(Test->testIt())")
     */
    public function iHaveSeveralAfterReturningAdvices($param1)
    {

    }
}
