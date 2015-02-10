<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\ChildTestClass
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

namespace AppserverIo\Doppelgaenger\Tests\Data;

/**
 * Class which is used to test inheritance of contracts
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class ChildTestClass extends ParentTestClass
{
    protected $elements;

    public function __construct()
    {
        $this->elements = array();
    }


    public function size()
    {
        return count($this->elements);
    }

    /**
     *
     */
    public function peek()
    {
        $tmp = array_pop($this->elements);
        array_push($this->elements, $tmp);

        return $tmp;
    }

    /**
     *
     */
    public function pop()
    {
        return array_pop($this->elements);
    }

    /**
     * @Ensures("in_array($obj, $this->elements)")
     */
    public function push(\Object $obj)
    {
        return array_push($this->elements, $obj);
    }
}
