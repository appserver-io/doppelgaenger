<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Functional\MethodTest
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

use AppserverIo\Doppelgaenger\Tests\Data\MagicMethodTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\MethodTestClass;

/**
 * Will test proper usage of magic functionality
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class MethodTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \AppserverIo\Doppelgaenger\Tests\Data\MagicMethodTestClass $magicMethodTestClass Data class which we will run our tests on
     */
    private $magicMethodTestClass;

    /**
     * Check if we can cope with the magic methods from MagicMethodTestClass
     *
     * @return null
     */
    public function testMagicMethod()
    {
        $this->magicMethodTestClass =
            new MagicMethodTestClass();
    }

    /**
     * Will test if the magic constants _DIR_ and _FILE_ get substituted correctly
     *
     * @return null
     */
    public function testMagicConstantSubstitution()
    {
        $methodTestClass = new MethodTestClass();

        $dir = $methodTestClass->returnDir();
        $this->assertEquals($dir, str_replace(DIRECTORY_SEPARATOR . 'Functional', '', __DIR__ . DIRECTORY_SEPARATOR . 'Data'));

        $file = $methodTestClass->returnFile();
        $this->assertEquals(
            $file,
            str_replace(DIRECTORY_SEPARATOR . 'Functional', '', __DIR__) .
            DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'MethodTestClass.php'
        );
    }
}
