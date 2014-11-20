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
 * AppserverIo\Doppelgaenger\Tests\Data\InterfaceInterface
 *
 * Interface which provides contracts to be implemented by classes
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
interface InterfaceInterface
{
    public function isConsistent();

    public function size();

    /**
     * @requires $this->size() >= 1
     */
    public function peek();

    /**
     * @requires $this->size() >= 1
     * @ensures $this->size() == $pbcOld->size() - 1
     * @ensures $pbcResult == $pbcOld->peek()
     */
    public function pop();

    /**
     * @ensures $this->size() == $pbcOld->size() + 1
     * @ensures $this->peek() == $obj
     */
    public function push($obj);
}
