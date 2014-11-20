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
 * @package     TechDivision\Tests
 * @subpackage  Property
 * @copyright   Copyright (c) 2013 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Bernhard Wick <b.wick@techdivision.com>
 *
 * @invariant   $this->privateCheckedProperty === 'test'
 * @invariant   $this->protectedCheckedProperty === 1
 * @invariant   $this->publicCheckedProperty === 27.42
 */
class PropertyTestClass
{
    private $privateNonCheckedProperty;

    private $privateCheckedProperty = 'test';

    protected $protectedNonCheckedProperty;

    protected $protectedCheckedProperty = 1;

    public $publicNonCheckedProperty;

    public $publicCheckedProperty = 27.42;
}
