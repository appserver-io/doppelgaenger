<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\ParserTest\DocBlockPositioningTestClass
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

/**
 * Test class for testing DocBlock positioning
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class DocBlockPositioningTestClass
{
    /**
     * Test
     *
     * @return string
     */

    /**
     * Test
     *
     * @return integer
     */
    public function iWillFailPostcondition()
    {
        return \stdClass::class;
    }

    public function iDontHaveADocBlock1()
    {
        return \stdClass::class;
    }

    /**
     * Test
     *
     * @return integer
     */


    public function iDontHaveADocBlock2()
    {
        return \stdClass::class;
    }
}
