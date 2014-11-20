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
 * @subpackage Interfaces
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\Interfaces;

/**
 * AppserverIo\Doppelgaenger\Interfaces\TypedListInterface
 *
 * Public interface for type safe list objects
 *
 * @category   Doppelgaenger
 * @package    AppserverIo\Doppelgaenger
 * @subpackage Interfaces
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
interface TypedListInterface
{
    /**
     * Will return an entry for a certain offset
     *
     * @param mixed $value The offset of the entry
     *
     * @return mixed
     */
    public function getOffset($value);

    /**
     * Checks if an offset exists.
     *
     * @param mixed $offset The offset to check for
     *
     * @return bool
     */
    public function entryExists($offset);

    /**
     * Will delete an entry at a certain offset
     *
     * @param mixed $offset The offset to delete at
     *
     * @return void
     */
    public function delete($offset);

    /**
     * Will return a certain entry
     *
     * @param mixed $offset The offset to get
     *
     * @return mixed
     */
    public function get($offset);

    /**
     * Will set an entry at a certain offset. Existing entries will be overwritten
     *
     * @param mixed $offset The offset on which we will set
     * @param mixed $value  The value to set
     *
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function set($offset, $value);

    /**
     * Will add an entry to the container. The offset will be set automatically
     *
     * @param mixed $value The value to add
     *
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function add($value);

    /**
     * Will attach another typed list to this list
     *
     * @param \AppserverIo\Doppelgaenger\Interfaces\TypedListInterface $foreignList The list to attach to this list
     *
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function attach(TypedListInterface $foreignList);

    /**
     * Will return an ArrayIterator object for this list
     *
     * @return \ArrayIterator
     */
    public function getIterator();

    /**
     * Will return the entry count
     *
     * @return int
     */
    public function count();
}
