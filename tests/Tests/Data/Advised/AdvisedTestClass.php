<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\Advised\AdvisedTestClass
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
 * Class used as a target of aspect based pointcuts
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class AdvisedTestClass
{

    /**
     * Method used as a target for aspect based advice weaving
     *
     * @return boolean
     */
    public function publicSimpleMethod()
    {
        return false;
    }

    /**
     * Method used to test for aspect based around advice chaining
     *
     * @return array
     */
    public function aroundChainMethod()
    {
        return array();
    }

    /**
     * Will return false
     *
     * @return boolean
     */
    public function falseMethod1()
    {
        return false;
    }

    /**
     * Will return false
     *
     * @return boolean
     */
    public function falseMethod2()
    {
        return false;
    }
}
