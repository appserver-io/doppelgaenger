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
 * @package    AppserverIo\Doppelgaenger
 * @subpackage Tests
 * @copyright  Copyright (c) 2013 <info@techdivision.com> - TechDivision GmbH
 * @license    http://opensource.org/licenses/osl-3.0.php
 *             Open Software License (OSL 3.0)
 * @author     Bernhard Wick <b.wick@techdivision.com>
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
