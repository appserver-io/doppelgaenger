<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Functional\Parser\ClassParserTest
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

namespace AppserverIo\Doppelgaenger\Tests\Functional\Parser;

use AppserverIo\Doppelgaenger\Config;
use AppserverIo\Doppelgaenger\Parser\ClassParser;

/**
 * Some functional tests for the class parser
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class ClassParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Instance of our test class
     *
     * @var \AppserverIo\Doppelgaenger\Parser\ClassParser $testClass
     */
    protected $testClass;

    /**
     * Instance of the result produces by our test class
     *
     * @var \AppserverIo\Doppelgaenger\Entities\Definitions\ClassDefinition $resultClass
     */
    protected $resultClass;

    /**
     * Default constructor
     */
    public function __construct()
    {
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' .
            DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'ParserTest' . DIRECTORY_SEPARATOR . 'BasicTestClass.php';
        $this->testClass = new ClassParser($filePath, new Config());
        $this->resultClass = $this->testClass->getDefinition();
    }

    /**
     * Tests if the parser produces an instance of the expected class
     *
     * @return void
     */
    public function testInstanceOfClassDefinition()
    {
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Entities\Definitions\ClassDefinition', $this->resultClass);
    }

    /**
     * Tests if the used structures are picked up correctly
     *
     * @return void
     */
    public function testUsedStructures()
    {
        $this->assertCount(2, $this->resultClass->getUsedStructures());
    }
}
