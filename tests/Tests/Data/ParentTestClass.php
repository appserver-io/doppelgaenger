<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\ParentTestClass
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
 * Class used as a parent introducing contracts which can be inherited by the child classes
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 *
 * @invariant $this->size() >= 0
 * @invariant $this->size() < 100
 * @invariant $this->elements !== null
 */
class ParentTestClass
{
    public function size()
    {

    }

    /**
     * @requires $this->size() >= 1
     */
    public function peek()
    {

    }

    /**
     * @requires $this->size() >= 1
     * @ensures $this->size() == $dgOld->size() - 1
     * @ensures $dgResult == $dgOld->peek()
     */
    public function pop()
    {

    }

    /**
     * @ensures $this->size() == $dgOld->size() + 1
     * @ensures $this->peek() == $obj
     */
    public function push(\Object $obj)
    {

    }
}
