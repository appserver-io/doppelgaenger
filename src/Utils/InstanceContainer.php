<?php

/**
 * \AppserverIo\Doppelgaenger\Utils\InstanceContainer
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

namespace AppserverIo\Doppelgaenger\Utils;

/**
 * Provides a static container to provide instances we want to inject into generated code
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class InstanceContainer implements \ArrayAccess
{
    /**
     * The actual container where instances are stored
     *
     * @var array $container
     */
    protected static $container = array();

    /**
     * Will check if an offset exists within the container
     *
     * @param mixed $offset The offset to check for
     *
     * @return boolean
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     */
    public function offsetExists($offset)
    {
        return isset(self::$container[$offset]);
    }

    /**
     * Returns a value at a certain offset
     *
     * @param mixed $offset The offset to get
     *
     * @return mixed
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     */
    public function offsetGet($offset)
    {
        return self::$container[$offset];
    }

    /**
     * Sets a value at a certain offset
     *
     * @param mixed $offset Offset to save the value at
     * @param mixed $value  The value to set at the given offset
     *
     * @return void
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     */
    public function offsetSet($offset, $value)
    {
        self::$container[$offset] = $value;
    }

    /**
     * Unsets the value at the given offset
     *
     * @param mixed $offset The offset at which to unset
     *
     * @return void
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     */
    public function offsetUnset($offset)
    {
        // Only offset if there even is a value
        if ($this->offsetExists($offset)) {
            unset(self::$container[$offset]);
        }
    }
}
