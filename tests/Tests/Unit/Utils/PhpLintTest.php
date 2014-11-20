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

use AppserverIo\Doppelgaenger\Utils\PhpLint;

/**
 * AppserverIo\Doppelgaenger\Tests\Unit\Utils\PhpLintTest
 *
 * Unit test of PhpLint class
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class PhpLintTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Will test the check() method
     *
     * @return void
     */
    public function testCheck()
    {

        // Get the lint
        $lint = new PhpLint();

        // Make one successful test where we have to remove some tags
        $this->assertTrue($lint->check('<?php $test = true;'));

        // Make one successful test where we do not have to do anything
        $this->assertTrue($lint->check('$test = true;'));

        // Make some tests which fail
        $this->assertFalse($lint->check('$test ====== true;'));
        $this->assertFalse($lint->check('$test = true'));
    }
}
