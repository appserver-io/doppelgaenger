<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Introduction
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

namespace AppserverIo\Doppelgaenger\Entities;

/**
 * Class which represents the introduction of additional characteristics to a target class
 * Technique is also known as inter-type declaration
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class Introduction
{

    /**
     * Name of a trait which is used to provide an implementation of the introduces interface.
     * Must be fully qualified or already known to the target's namespace
     *
     * @var string $implementation
     */
    protected $implementation;

    /**
     * Name of the interface which is used to extend the target's characteristics.
     * Must be fully qualified or already known to the target's namespace
     *
     * @var string $interface
     */
    protected $interface;

    /**
     * Name of the target class which gets new characteristics introduced
     * Might also be a PCRE which will match several classes
     *
     * @var string $target
     */
    protected $target;

    /**
     * Getter for the $implementation property
     *
     * @return string
     */
    public function getImplementation()
    {
        return $this->implementation;
    }

    /**
     * Getter for the $interface property
     *
     * @return string
     */
    public function getInterface()
    {
        return $this->interface;
    }

    /**
     * Getter for the $target property
     *
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Setter for the $implementation property
     *
     * @param string $implementation Name of the trait used to implement functionality
     *
     * @return null
     */
    public function setImplementation($implementation)
    {
        $this->implementation = $implementation;
    }

    /**
     * Setter for the $interface property
     *
     * @param string $interface The interface describing the functionality
     *
     * @return null
     */
    public function setInterface($interface)
    {
        $this->interface = $interface;
    }

    /**
     * Setter for the $target property
     *
     * @param string $target Class which the implementation gets introduced to
     *
     * @return null
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }
}
