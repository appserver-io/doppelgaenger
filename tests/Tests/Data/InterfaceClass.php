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

namespace AppserverIo\Doppelgaenger\Tests\Data;

/**
 * AppserverIo\Doppelgaenger\Tests\Data\InterfaceClass
 *
 * Class which implements a contracted interface
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class InterfaceClass implements InterfaceInterface
{
    private $elements = array();

    /**
     * @return bool
     */
    public function isConsistent()
    {
        return is_array($this->elements);
    }

    /**
     * @return int
     */
    public function size()
    {
        return count($this->elements);
    }

    /**
     * @return mixed
     */
    public function peek()
    {
        $tmp = array_pop($this->elements);
        array_push($this->elements, $tmp);

        return $tmp;
    }

    /**
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->elements);
    }

    /**
     * @param $obj
     *
     * @return int
     */
    public function push($obj)
    {
        return array_push($this->elements, $obj);
    }
}
