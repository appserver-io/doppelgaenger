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

namespace AppserverIo\Doppelgaenger\Tests\Data\Advised;

/**
 * AppserverIo\Doppelgaenger\Tests\Data\Advised\AdvisedTestClass
 *
 * Class used as a target of aspect based pointcuts
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
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
