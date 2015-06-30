<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Unit\Utils\FormattingTest
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

namespace AppserverIo\Doppelgaenger\Tests\Unit\Utils;

use AppserverIo\Doppelgaenger\Utils\Formatting;

/**
 * Test for the AppserverIo\Doppelgaenger\Utils\Formatting class
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
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

    /**
     * Will provide test pathes for our sanitation
     *
     * @return array
     */
    public function sanitizeSeparatorsProvider()
    {
        return  array(
            array('C:\test/testinger/test', '/', 'C:/test/testinger/test'),
            array('C:\test/testinger/test', '\\', 'C:\test\testinger\test'),
            array('/var\tmp', '\\', '\var\tmp'),
            array('/var\tmp', '/', '/var/tmp')
        );
    }

    /**
     * Will test if we can sanitize different pathes
     *
     * @param string $testPath       The path to sanitize
     * @param string $separator      The separator
     * @param string $expectedResult The expected result
     *
     * @return void
     *
     * @dataProvider sanitizeSeparatorsProvider
     */
    public function testSanitizeSeparators($testPath, $separator, $expectedResult)
    {
        $formatter = new Formatting();
        $this->assertEquals($expectedResult, $formatter->sanitizeSeparators($testPath, $separator));
    }
}
