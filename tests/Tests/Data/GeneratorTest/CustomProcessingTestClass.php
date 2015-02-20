<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\GeneratorTest\CustomProcessingTestClass
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

namespace AppserverIo\Doppelgaenger\Tests\Data\GeneratorTest;

/**
 * Class used to test the functionality of the RespectValidation assertion type
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 *
 * @Processing("logging")
 */
class CustomProcessingTestClass
{

    /**
     * @Ensures("$dgResult === 'This will never work'")
     * @Processing("exception")
     */
    public function iHaveACustomExceptionProcessing()
    {

    }

    /**
     * @Ensures("$dgResult === 'This will never work'")
     */
    public function iHaveNoCustomProcessing()
    {

    }
}
