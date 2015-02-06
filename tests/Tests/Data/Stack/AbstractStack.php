<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\AdvisedTestClass
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

namespace AppserverIo\Doppelgaenger\Tests\Data\Stack;

/**
 * Class used as a target of aspect based pointcuts
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 *
 * @Invariant is_array($this->container)
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
     * @Ensures is_int($dgResult)
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
     * @Requires $this->size() >= 1
     * @Ensures $this->size() === $dgOld->size()
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
     * @Requires $this->size() >= 1
     * @Ensures $this->size() == $dgOld->size() - 1
     * @Ensures $dgResult == $dgOld->peek()
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
     * @Ensures $this->size() == $dgOld->size() + 1
     * @Ensures $this->peek() == $obj
     */
    public function push($obj)
    {
        return array_push($this->container, $obj);
    }
}
