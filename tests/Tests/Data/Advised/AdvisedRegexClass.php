<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\Advised\AdvisedRegexClass
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

namespace AppserverIo\Doppelgaenger\Tests\Data\Advised;

/**
 * Class used to test if certain pointcut expressions containing regex work
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class AdvisedRegexClass
{
    /**
     * Method to be advised using regex expressions
     *
     * @return boolean
     */
    public function regexClassMethod()
    {
        return true;
    }

    /**
     * Method to be advised using regex expressions
     *
     * @return boolean
     */
    public function regexMethodMethod()
    {
        return true;
    }
}
