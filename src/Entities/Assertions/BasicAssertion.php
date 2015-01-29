<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Assertions\BasicAssertion
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

namespace AppserverIo\Doppelgaenger\Entities\Assertions;

/**
 * Basic assertions to compare two values
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class BasicAssertion extends AbstractAssertion
{
    /**
     * @var string $firstOperand The first operand to compare
     */
    public $firstOperand;

    /**
     * @var string $secondOperand The second operand to compare
     */
    public $secondOperand;

    /**
     * @var string $operator The operator used for comparison
     */
    public $operator;

    /**
     * @var array $inversionMapping A map to inverse operators
     */
    protected $inversionMapping;

    /**
     * Default constructor
     *
     * @param string $firstOperand  The first operand to compare
     * @param string $secondOperand The second operand to compare
     * @param string $operator      The operator used for comparison
     */
    public function __construct($firstOperand = '', $secondOperand = '', $operator = '')
    {
        $this->firstOperand = $firstOperand;
        $this->secondOperand = $secondOperand;
        $this->operator = $operator;

        // Set the mapping for our inversion
        $this->inversionMapping = array(
            '==' => '!=',
            '===' => '!==',
            '<>' => '==',
            '<' => '>=',
            '>' => '<=',
            '<=' => '>',
            '>=' => '<',
            '!=' => '==',
            '!==' => '==='
        );

        parent::__construct();
    }

    /**
     * Will return a string representation of this assertion
     *
     * @return string
     */
    public function getString()
    {
        return (string)$this->firstOperand . ' ' . $this->operator . ' ' . $this->secondOperand;
    }

    /**
     * Will return an inverted string representation.
     * Implemented here, as we want to check if there is an entry in our inversion map we can use
     *
     * @return string
     */
    public function getInvertString()
    {
        if (isset($this->inversionMapping[$this->operator])) {
            // only return if we found something
            return (string)$this->firstOperand . ' ' .
            $this->inversionMapping[$this->operator] . ' ' . $this->secondOperand;
        }
    }

    /**
     * Invert the logical meaning of this assertion
     *
     * @return bool
     */
    public function invert()
    {
        if (isset($this->inversionMapping[$this->operator])) {
            // Invert the operator
            $this->operator = $this->inversionMapping[$this->operator];
            // Don't forget to mark this assertion as inverted
            if ($this->inverted === true) {
                $this->inverted = false;

            } else {
                $this->inverted = true;
            }

            return true;

        } else {
            return false;
        }
    }
}
