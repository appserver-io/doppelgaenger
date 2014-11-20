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
 *
 * @invariant   $this->attributes['test1'] === 'test1'
 */
class MagicMethodTestClass
{
    /**
     *
     * @var type
     */
    public $attributes = array('test1' => 'test1', 'test2');

    /**
     *
     */
    public function __construct()
    {

    }

    /**
     *
     * @param type $name
     *
     * @return null
     */
    public function __get($name)
    {

        if (isset($this->attributes[$name])) {

            return $this->attributes[$name];

        } else {

            return null;
        }
    }

    /**
     *
     * @param type $name
     * @param type $value
     *
     * @return boolean
     */
    public function __set($name, $value)
    {

        if (isset($this->attributes[$name])) {

            $this->attributes[$name] = $value;

            return true;

        } else {

            return false;
        }
    }
}
