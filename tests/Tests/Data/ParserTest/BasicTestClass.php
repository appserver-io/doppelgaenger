<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\ParserTest\BasicTestClass
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

namespace AppserverIo\Doppelgaenger\Tests\Data\ParserTest;

use AppserverIo\Doppelgaenger\Config;
use AppserverIo\Doppelgaenger\Parser\ClassParser;

/**
 * Test class for testing class support
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class BasicTestClass
{
    // use a basic trait here to check for the correct parsing of "use" statements
    use BasicTestTrait;

    /**
     * Test
     *
     * @return string
     */
    public function test()
    {
        return \stdClass::class;
    }

    /**
     * Test method containing a method call with an anonymous callback function as parameter.
     * Contains an "use" statement
     *
     * @return void
     */
    public function methodWithAnAnonymousCallback()
    {
        $test = 'test';
        $this->test(function($param) use($test) {
            // do nothing
        });
    }

    /**
     * Test method containing a method call with an anonymous callback function as parameter.
     * Contains no "use" statement
     *
     * @return void
     */
    public function methodWithAnotherAnonymousCallback()
    {
        $this->test(function () {
            // do nothing
        });
    }
}
