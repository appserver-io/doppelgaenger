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
 * @Invariant("$this->size() <= $this->limit")
 */
class FixedStack extends AbstractStack
{
    /**
     * Limit for the stack's size
     *
     * @var int $limit
     */
    protected $limit;

    /**
     * Default constructor
     *
     * @Requires("is_int($_limit)")
     */
    public function __construct($_limit)
    {
        $this->limit = $_limit;
    }

    /**
     * Will push a given element to the stack
     *
     * @param mixed $obj The element to push
     *
     * @return null
     *
     * @Requires("$this->size() < $this->limit")
     */
    public function push($obj)
    {
        return parent::push($obj);
    }
}
