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

namespace AppserverIo\Doppelgaenger\Tests\Unit\Utils;

use AppserverIo\Doppelgaenger\Utils\Formatting;

/**
 * AppserverIo\Doppelgaenger\Tests\Unit\Utils\FormattingTest
 *
 * Test for the AppserverIo\Doppelgaenger\Utils\Formatting class
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class FormattingTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Will test the toRegex() method
     *
     * @return void
     */
    public function testToRegex()
    {
        $formatter = new Formatting();
        $testString = '$()*[] /';
        $this->assertEquals('\$\(\)\*\[\]\s*\/', $formatter->toRegex($testString));
    }
}
