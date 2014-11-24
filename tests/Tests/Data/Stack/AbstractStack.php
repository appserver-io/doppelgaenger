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
 * @invariant is_array($this->container)
 */
class AbstractStack
{
    /**
     * Container for the stack elements
     *
     * @var array $container
     */
    protected $container = array();

    /**
     * Returns stack size
     *
     * @return integer
     *
     * @ensures is_int($pbcResult)
     */
    public function size()
    {
        return count($this->container);
    }

    /**
     * Will return the first stack element without popping it
     *
     * @return mixed
     *
     * @requires $this->size() >= 1
     * @ensures $this->size() === $pbcOld->size()
     */
    public function peek()
    {
        $tmp = array_pop($this->container);
        array_push($this->container, $tmp);

        return $tmp;
    }

    /**
     * Will pop the first stack element
     *
     * @return mixed
     *
     * @requires $this->size() >= 1
     * @ensures $this->size() == $pbcOld->size() - 1
     * @ensures $pbcResult == $pbcOld->peek()
     */
    public function pop()
    {
        return array_pop($this->container);
    }

    /**
     * Will push a given element to the stack
     *
     * @param mixed $obj The element to push
     *
     * @return null
     *
     * @ensures $this->size() == $pbcOld->size() + 1
     * @ensures $this->peek() == $obj
     */
    public function push($obj)
    {
        return array_push($this->container, $obj);
    }
}
