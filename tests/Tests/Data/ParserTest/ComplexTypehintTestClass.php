<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\ParserTest\ComplexTypehintTestClass
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
use AppserverIo\Doppelgaenger\Utils\Parser;

/**
 * Class used to test
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class ComplexTypehintTestClass
{

    /**
     * Method which does have a shortened return value
     *
     * @return Config
     */
    public function iHaveAShortReturnComment()
    {
        return new \stdClass();
    }

    /**
     * Method which does have a shortened return value
     *
     * @return BasicTestClass
     */
    public function iHaveALocalClassReturnComment()
    {
        return new \stdClass();
    }

    /**
     * Method which does have a shortened return value
     *
     * @return BasicTestClass
     */
    public function iHaveALocalClassReturnCommentAndSucceed()
    {
        return new BasicTestClass();
    }

    /**
     * Method which does have a fully qualified return value
     *
     * @return \AppserverIo\Doppelgaenger\Utils\Parser
     */
    public function iHaveAQualifiedReturnCommentAndSucceed()
    {
        return new Parser();
    }
}
