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

namespace AppserverIo\Doppelgaenger\Entities\Assertions;

/**
 * AppserverIo\Doppelgaenger\Entities\Assertions\InstanceAssertion
 *
 * This class is used to provide an object base way to pass assertions as e.g. a precondition.
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class InstanceAssertion extends AbstractAssertion
{
    /**
     * @var string $operand The operand we have to check
     */
    public $operand;

    /**
     * @var string $class The name of the class we have to check for
     */
    public $class;

    /**
     * Default constructor
     *
     * @param string $operand The operand we have to check
     * @param string $class   The name of the class we have to check for
     */
    public function __construct($operand, $class)
    {
        $this->operand = $operand;
        $this->class = $class;

        parent::__construct();
    }

    /**
     * Will return a string representation of this assertion
     *
     * @return string
     */
    public function getString()
    {
        // We need to add an initial backslash if there is none
        if (strpos($this->class, '\\') > 0) {

            $this->class = '\\' . $this->class;
        }

        return (string)$this->operand . ' instanceof ' . $this->class;
    }

    /**
     * Invert the logical meaning of this assertion
     *
     * @return bool
     */
    public function invert()
    {
        if ($this->inverted !== true) {

            $this->operand = '!' . $this->operand;
            $this->inverted = true;

            return true;

        } elseif ($this->inverted === true) {

            $this->operand = ltrim($this->operand, '!');
            $this->inverted = false;

            return true;

        } else {

            return false;
        }
    }
}
