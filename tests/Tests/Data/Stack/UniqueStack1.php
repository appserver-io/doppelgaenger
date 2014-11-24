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

namespace AppserverIo\Doppelgaenger\Tests\Data\Stack;

/**
 * AppserverIo\Doppelgaenger\Tests\Data\AdvisedTestClass
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
 *
 * @invariant $this->isValid()
 */
class UniqueStack1 extends AbstractStack
{
    /**
     * Will test if the stack is valid in itself
     *
     * @return boolean
     */
    private function isValid()
    {
        if (count($this->container) !== count(array_unique($this->container))) {

            return false;

        } else {

            return true;
        }
    }
}
