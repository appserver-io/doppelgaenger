<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Functional\StructureMapTest
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

namespace AppserverIo\Doppelgaenger\Tests\Functional;

use AppserverIo\Doppelgaenger\Config;
use AppserverIo\Doppelgaenger\StructureMap;

/**
 * Some functional tests for the StructureMap functionality
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class StructureMapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Directories that contain the data needed for tests
     *
     * @var array $dataDirs
     */
    protected $dataDirs;

    /**
     * Instance of a prepared structure map.
     * Create your own if you
     *
     * @var \AppserverIo\Doppelgaenger\StructureMap $structureMap
     */
    protected $structureMap;

    /**
     * Set upt the test environment
     *
     * @return null
     */
    public function setUp()
    {
        $this->dataDirs = array(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Data');

        // get the objects we need
        $config = new Config();
        $config->setValue('autoload/dirs', $this->dataDirs);
        $config->setValue('enforcement/dirs', array());
        $this->structureMap = new StructureMap(
            $config->getValue('autoload/dirs'),
            $config->getValue('enforcement/dirs'),
            $config
        );

        // fill the map
        $this->structureMap->fill();
    }

    /**
     * Will test if classes with underscores in their name can get processed the right way
     *
     * @return null
     */
    public function testWithUnderscoredClass()
    {
        // test if we have the entry for the underscored class
        $this->assertTrue($this->structureMap->entryExists('Random\Test\NamespaceName\Underscored_Class'));
    }

    /**
     * Will test if classes with huge class doc comments can be picked up correctly
     *
     * @return null
     */
    public function testWithHugeClassDocBlockClass()
    {
        // test if we have the entry for the underscored class
        $this->assertTrue($this->structureMap->entryExists('Random\Test\NamespaceName\HugeClassDocBlockClass'));
    }

    /**
     * Will test if classes which have the namespace in the same line as the PHP tag can be parsed correctly
     *
     * @return null
     */
    public function testWithNamespaceInFirstLine()
    {
        // test if we have the entry for the underscored class
        $this->assertTrue($this->structureMap->entryExists('Random\Test\NamespaceName\NamespaceInFirstLineClass'));
    }
}
