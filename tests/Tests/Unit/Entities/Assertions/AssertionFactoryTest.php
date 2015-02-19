<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Unit\Entities\Assertions\AssertionFactoryTest
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

namespace AppserverIo\Doppelgaenger\Tests\Unit\Entities\Assertions;

use AppserverIo\Doppelgaenger\Entities\Assertions\AssertionFactory;

/**
 * Unittests for our AssertionFactory class
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class AssertionFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Instance of our test class
     *
     * @var \AppserverIo\Doppelgaenger\Entities\Assertions\AssertionFactory $testClass
     */
    protected $testClass;

    /**
     * Get our test class
     *
     * @return null
     */
    public function setUp()
    {
        $this->testClass = new AssertionFactory();
    }

    /**
     * Will test if valid scalar type array is kept sane
     *
     * @return null
     */
    public function testGetValidScalarTypes()
    {
        $validScalarTypes = $this->testClass->getValidScalarTypes();

        $this->assertContains('void', $validScalarTypes);
        $this->assertContains('string', $validScalarTypes);
        $this->assertNotContains('mixed', $validScalarTypes);
    }
}
