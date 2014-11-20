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

use AppserverIo\Doppelgaenger\Config;

/**
 * AppserverIo\Doppelgaenger\Tests\Unit\BasicTest
 *
 * This test will test the configuration class AppserverIo\Doppelgaenger\Config
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test the static getInstance() method
     *
     * @return void
     */
    public function testGetInstance()
    {
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Config', new Config());
    }

    /**
     * Test the setValue() method
     *
     * @return void
     */
    public function testSetValue()
    {
        // Get our config
        $config = new Config();

        // Set a simple value and test if it got set
        $config->setValue('environment', 'testing');
        $this->assertEquals('testing', $config->getValue('environment'));

        // Set a more complex value and test if it got set
        $config->setValue('autoloader/dirs', array(1, 2, 3));
        $this->assertEquals(array(1, 2, 3), $config->getValue('autoloader/dirs'));
    }

    /**
     * Test the getValue() method
     *
     * @return void
     */
    public function testGetValue()
    {
        // Get our config
        $config = new Config();

        // Test the values as they came from the default config
        $this->assertEquals('production', $config->getValue('environment'));
        $this->assertEquals(7, $config->getValue('enforcement/level'));
    }

    /**
     * Test the extendValue() method
     *
     * @return void
     *
     * @depends testSetValue
     * @depends testGetValue
     */
    public function testExtendValue()
    {
        // Get our config
        $config = new Config();

        // Test string concatination
        $config->extendValue('environment', 'test');
        $this->assertEquals('productiontest', $config->getValue('environment'));

        // Test array extension
        $config->extendValue('enforcement/omit', array('Tests'));
        $this->assertEquals(array('PHPUnit', 'Psr\Log', 'PHP', 'Tests'), $config->getValue('enforcement/omit'));
    }

    /**
     * Test the hasValue() method
     *
     * @return void
     */
    public function testHasValue()
    {
        // Get our config
        $config = new Config();

        // Test with something we know exists
        $this->assertTrue($config->hasValue('environment'));

        // And something we know that does not
        $this->assertFalse($config->hasValue(__METHOD__ . time()));
    }

    /**
     * Test the unsetValue() method
     *
     * @return void
     *
     * @depends testHasValue
     */
    public function testUnsetValue()
    {
        // Get our config
        $config = new Config();

        // Unset some values and test if they do not exist anymore
        $this->assertTrue($config->hasValue('environment'));
        $config->unsetValue('environment');
        $this->assertFalse($config->hasValue('environment'));

        // Do the same for a more "complex" index
        $this->assertTrue($config->hasValue('enforcement/enforce-default-type-safety'));
        $config->unsetValue('enforcement/enforce-default-type-safety');
        $this->assertFalse($config->hasValue('enforcement/enforce-default-type-safety'));
    }
}
