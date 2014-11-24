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
 */
class StringStack extends AbstractStack
{
    /**
     * Will return the first stack element without popping it
     *
     * @return mixed
     *
     * @ensures is_string($pbcResult)
     */
    public function peek()
    {
        return parent::peek();
    }

    /**
     * Will pop the first stack element
     *
     * @return mixed
     *
     * @ensures is_string($pbcResult)
     */
    public function pop()
    {
        return parent::pop();
    }

    /**
     * Will push a given element to the stack
     *
     * @param mixed $obj The element to push
     *
     * @return null
     */
    public function push($obj)
    {
        return parent::push($obj);
    }
}
