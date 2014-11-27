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
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\Entities\Lists;

use AppserverIo\Doppelgaenger\Interfaces\TypedListInterface;

/**
 * AppserverIo\Doppelgaenger\Entities\Lists\AbstractTypedList
 *
 * Abstract parent class for type safe list structures
 * This class has dynamically defined properties as there are problems with earlier versions of extensions like pthreads!
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 *
 * @property array   $container     The actual container holding the entries
 * @property integer $position      Keeps track of the currently iterated position
 * @property string  $defaultOffset Default member of an entity which will be used as offset e.g. "name"
 * @property string  $itemType      Type of the contained elements
 * @property array   $keyTracker    Internally used array which provides a mapping for associative offset keys to integer ones, which allows for a more easy iteration process
 */
abstract class AbstractTypedList implements TypedListInterface, \Iterator
{

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->container = array();
        $this->currentPosition = 0;
        $this->defaultOffset = '';
        $this->itemType = '';
        $this->keyTracker = array();
    }

    /**
     * Will add an entry to the container. The offset will be set automatically
     *
     * @param mixed $value The value to add
     *
     * @throws \UnexpectedValueException
     *
     * @return boolean|null
     */
    public function add($value)
    {
        if (!is_a($value, $this->itemType)) {

            throw new \UnexpectedValueException(sprintf('The added value does not have the expected type %s', $this->itemType));

        } else {

            $tmp = $this->defaultOffset;

            // Do we have a default offset?
            if (!empty($tmp)) {

                // Default offset is a member of the object to store, but how to access it? Try getter and direct access
                $getter = 'get' . ucfirst($tmp);
                if (method_exists($value, $getter)) {

                    $this->container[$value->$getter()] = $value;

                } elseif (property_exists($value, $tmp)) {

                    $this->container[$value->$tmp] = $value;
                }

            } else {

                // Lets check if we already got this entry, nothing we want!
                foreach ($this->container as $entry) {

                    // Doe we have this entry already? If so we did our duty
                    if ($value == $entry) {

                        return true;
                    }
                }

                // Still here? Then add the value to the container
                $this->container[] = $value;
            }

            // update the key tracking
            $this->keyTracker = array_keys($this->container);
        }
    }

    /**
     * Will attach another typed list to this list
     *
     * @param \AppserverIo\Doppelgaenger\Interfaces\TypedListInterface $foreignList The list to attach to this list
     *
     * @throws \UnexpectedValueException
     *
     * @return void
     */
    public function attach(TypedListInterface $foreignList)
    {
        $iterator = $foreignList->getIterator();
        for ($i = 0; $i < $iterator->count(); $i++) {

            try {

                $this->add($iterator->current());

            } catch (\UnexpectedValueException $e) {

                throw $e;
            }

            $iterator->next();
        }
    }

    /**
     * Will return the entry count
     *
     * @return int
     */
    public function count()
    {
        return count($this->container);
    }

    /**
     * Will return the container entry at the current iteration position
     *
     * @return mixed
     */
    public function current()
    {

        return $this->container[$this->keyTracker[$this->currentPosition]];
    }

    /**
     * Will delete an entry at a certain offset
     *
     * @param mixed $offset The offset to delete at
     *
     * @return void
     */
    public function delete($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Checks if an offset exists.
     *
     * @param mixed $offset The offset to check for
     *
     * @return bool
     */
    public function entryExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Will return a certain entry
     *
     * @param mixed $offset The offset to get
     *
     * @return mixed
     */
    public function get($offset)
    {
        if (isset($this->container[$offset])) {

            return $this->container[$offset];

        } else {

            return false;
        }
    }

    /**
     * Will return an ArrayIterator object for this list
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->container);
    }

    /**
     * Will return an entry for a certain offset
     *
     * @param mixed $value The offset of the entry
     *
     * @return mixed
     */
    public function getOffset($value)
    {
        $iterator = $this->getIterator();
        for ($i = 0; $i < $iterator->count(); $i++) {

            if ($iterator->current() === $value) {

                return true;
            }

            // Move the iterator
            $iterator->next();
        }

        // We found nothing
        return false;
    }

    /**
     * Checks if the list is empty
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->container);
    }

    /**
     * Will return the key for the current iteration cycle
     *
     * @return string|integer
     */
    public function key()
    {

        return $this->keyTracker[$this->currentPosition];
    }

    /**
     * Will iterate the current position key
     *
     * @return null
     */
    public function next()
    {

        ++$this->currentPosition;
    }

    /**
     * Will rewind the current iteration key so next iteration will start with the first element
     *
     * @return null
     */
    public function rewind()
    {

        $this->currentPosition = 0;
    }

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
    public function set($offset, $value)
    {
        if (!is_a($value, $this->itemType)) {

            throw new \UnexpectedValueException();

        } else {

            $this->container[$offset] = $value;

            // update the key tracking
            $this->keyTracker = array_keys($this->container);
        }
    }

    /**
     * Checks if there is a valid value at the current cursor position
     *
     * @return boolean
     */
    public function valid()
    {

        return (isset($this->keyTracker[$this->currentPosition]) &&
            isset($this->container[$this->keyTracker[$this->currentPosition]]));
    }
}
