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

namespace AppserverIo\Doppelgaenger\Tests\Unit;

use AppserverIo\Doppelgaenger\AspectRegister;

/**
 * AppserverIo\Doppelgaenger\Tests\Unit\AspectRegisterTest
 *
 * Test class for the AspectRegister class
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class AspectRegisterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The aspect register instance we will run our tests against
     *
     * @var \AppserverIo\Doppelgaenger\AspectRegister $aspectRegister
     */
    protected $aspectRegister;

    /**
     * Set the tests up with a new aspect register instance for each test
     */
    public function setUp()
    {
        $this->aspectRegister = new AspectRegister();
    }

    /**
     * Test for the lookupAdvice method
     *
     * @return null
     *
     * @depends testLookupEntries
     * @depends testLookupAspects
     */
    public function testLookupAdvice()
    {
    }

    /**
     * Test for the lookupAspects method
     *
     * @return null
     *
     * @depends testLookupEntries
     */
    public function testLookupAspects()
    {
    }

    /**
     * Test for the lookupEntries method
     *
     * @return null
     */
    public function testLookupEntries()
    {
    }

    /**
     * Test for the lookupPointcuts method
     *
     * @return null
     *
     * @depends testLookupEntries
     */
    public function testLookupPointcuts()
    {
    }

    /**
     * Test for the register method
     *
     * @return null
     */
    public function testRegister()
    {
    }
}
