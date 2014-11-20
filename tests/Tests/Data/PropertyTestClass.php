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
 * AppserverIo\Doppelgaenger\Tests\Data\PropertyTestClass
 *
 * Class having several properties with different visibilities.
 * Used to check for unified property access
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
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
