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

use AppserverIo\Doppelgaenger\Exceptions\ParserException;

/**
 * AppserverIo\Doppelgaenger\Entities\Assertions\TypeAssertion
 *
 * This class will enable us to check for basic types
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class TypeAssertion extends AbstractAssertion
{
    /**
     * @var string $operand The operand we have to check
     */
    public $operand;

    /**
     * @var string $type The type we have to check for
     */
    public $type;

    /**
     * @var bool $validatesTo The bool value we should test against
     */
    public $validatesTo;

    /**
     * Default constructor
     *
     * @param string $operand The operand we have to check
     * @param string $type    The type we have to check for
     */
    public function __construct($operand, $type)
    {
        $this->operand = $operand;
        $this->validatesTo = true;
        $this->type = $type;

        parent::__construct();
    }

    /**
     * Will return a string representation of this assertion. Will return false if the type is unknown.
     *
     * @return string
     *
     * @throws \AppserverIo\Doppelgaenger\Exceptions\ParserException
     */
    public function getString()
    {
        if (function_exists('is_' . $this->type)) {

            if ($this->validatesTo === true) {

                return (string)'is_' . $this->type . '(' . $this->operand . ')';

            } else {

                return (string)'!is_' . $this->type . '(' . $this->operand . ')';
            }

        } else {

            throw new ParserException(sprintf('%s does not seem to be scalar type.', $this->getString()));
        }
    }

    /**
     * Invert the logical meaning of this assertion
     *
     * @return boolean
     */
    public function invert()
    {
        if ($this->validatesTo === true) {

            $this->validatesTo = false;
            $this->inverted = true;

            return true;

        } elseif ($this->validatesTo === false) {

            $this->validatesTo = true;
            $this->inverted = false;

            return true;

        } else {

            return false;
        }
    }
}
