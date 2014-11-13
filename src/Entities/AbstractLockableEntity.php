<?php
/**
 * File containing the AbstractLockableEntity class
 *
 * PHP version 5
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Entities;

use AppserverIo\Doppelgaenger\Exceptions\IllegalAccessException;

/**
 * AppserverIo\Doppelgaenger\Entities\AbstractLockableEntity
 *
 * Abstract class for lockable entities.
 * Lockable means, that write access to child class properties is handled via magic setter and can be switched off
 * (but not switched of again) via the lock() method.
 * This is used to implement entities based on the DTO pattern (immutable) + the possibility to set attributes
 * dynamically during a more complex creation procedure.
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
abstract class AbstractLockableEntity
{
    /**
     * Flag for locking the entity to make it immutable
     *
     * @var bool $isLocked
     */
    protected $isLocked = false;

    /**
     * Will call the child's method with the passed arguments as long as the entity is not locked
     *
     * @param string $name      The name of the method we want to set
     * @param array  $arguments The arguments to the method
     *
     * @return null
     * @throws \InvalidArgumentException
     * @throws IllegalAccessException
     */
    public function __call($name, array $arguments)
    {
        // If we are locked tell them
        if ($this->isLocked) {

            throw new IllegalAccessException('The entity ' . get_called_class() . ' is in a locked state');
        }

        // If we do not have this method we should tell them
        if (!method_exists($this, $name)) {

            throw new \InvalidArgumentException('There is no method called ' . $name);
        }

        // Still here? call the method then
        call_user_func_array(array($this, $name), $arguments);
    }

    /**
     * Will return the child classes property if it exists
     *
     * @param string $attribute The name of the attribute we want to set
     *
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function __get($attribute)
    {
        // If we do not have this property we should tell them
        if (!property_exists($this, $attribute)) {

            throw new \InvalidArgumentException('There is no attribute called ' . $attribute);
        }

        // Still here? Get it then
        return $this->$attribute;
    }
    /**
     * Will lock the child entity and make it immutable (if there are no other means of access)
     *
     * @return null
     */
    public function lock()
    {
        $this->isLocked = true;
    }

    /**
     * Will set the child classes properties if the entity is not locked
     *
     * @param string $attribute The name of the attribute we want to set
     * @param mixed  $value     The value we want to assign to it
     *
     * @return null
     * @throws \InvalidArgumentException
     * @throws IllegalAccessException
     */
    public function __set($attribute, $value)
    {
        // If we are locked tell them
        if ($this->isLocked) {

            throw new IllegalAccessException('The entity ' . get_called_class() . ' is in a locked state');
        }

        // If we do not have this property we should tell them
        if (!property_exists($this, $attribute)) {

            throw new \InvalidArgumentException('There is no attribute called ' . $attribute);
        }

        // Still here? Set it then
        $this->$attribute = $value;
    }
}
