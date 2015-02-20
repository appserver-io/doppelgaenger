<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Assertions\AbstractAssertion
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

use AppserverIo\Doppelgaenger\Dictionaries\ReservedKeywords;
use AppserverIo\Doppelgaenger\Exceptions\ParserException;
use AppserverIo\Doppelgaenger\Interfaces\AssertionInterface;
use AppserverIo\Doppelgaenger\Utils\PhpLint;

/**
 * This class is used to provide an object base way to pass assertions as e.g. a precondition.
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
abstract class AbstractAssertion implements AssertionInterface
{
    /**
     * Minimal scope is "function" per default as we don't have DbC checks right know (would be body)
     *
     * @const string DEFAULT_MIN_SCOPE
     */
    const DEFAULT_MIN_SCOPE = 'function';

    /**
     * If the logical meaning was inverted
     *
     * @var boolean $inverted
     */
    protected $inverted;

    /**
     * If the error message produced by the assertion does not require wrapping by enforcement mechanisms
     *
     * @var boolean $needsWrapping
     */
    protected $needsWrapping;

    /**
     * If the assertion is only used in a private context. This will be used for inheritance to determine which
     * assertion has to be passed down to possible children.
     *
     * @var boolean $privateContext
     */
    protected $privateContext;

    /**
     * The minimal scope range we need so we are able to fulfill this assertion. E.g. if this assertion contains
     * a member variable our minimal scope will be "structure", if we compare parameters it will be "function".
     * Possible values are "structure", "function" and "body".
     *
     * @var string $minScope
     */
    protected $minScope;

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->inverted = false;
        $this->privateContext = false;

        $this->minScope = self::DEFAULT_MIN_SCOPE;

        if (!$this->isValid()) {
            throw new ParserException(sprintf('Could not parse assertion string %s', $this->getString()));
        }
    }

    /**
     * Will return a string representing the inverted logical meaning
     *
     * @return string
     */
    public function getInvertString()
    {
        // Invert a copy of this instance
        $self = clone $this;

        $self->invert();

        // Return the string of the inverted instance
        return $self->getString();
    }

    /**
     * Will return the minimal scope
     *
     * @return string
     */
    public function getMinScope()
    {
        return $this->minScope;
    }

    /**
     * Will set the minimal scope if you pass an allowed value ("structure", "function" and "body")
     *
     * @param string $minScope The value to set
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function setMinScope($minScope)
    {
        // If we did not get an allowed value we will throw an exception
        $tmp = array_flip(array("structure", "function", "body"));
        if (!isset($tmp[$minScope])) {
            throw new \InvalidArgumentException(
                sprintf('The minimal scope %s is not allowed. It may only be "structure", "function" or "body"', $minScope)
            );
        }

        // Set the new minimal scope
        $this->minScope = $minScope;
    }

    /**
     * Will return true if the assertion is in an inverted state
     *
     * @return boolean
     */
    public function isInverted()
    {
        return $this->inverted;
    }

    /**
     * Will return true if the assertion is only usable within a private context.
     *
     * @return boolean
     */
    public function isPrivateContext()
    {
        return $this->privateContext;
    }

    /**
     * Will test if the assertion will result in a valid PHP statement
     *
     * @return boolean
     */
    public function isValid()
    {
        // We need our lint class
        $lint = new PhpLint();

        // Wrap the code as a condition for an if clause
        return $lint->check('if(' . $this->getString() . '){}');
    }

    /**
     * Setter for the $privateContext attribute
     *
     * @param boolean $privateContext The value to set the private context to
     *
     * @return void
     */
    public function setPrivateContext($privateContext)
    {
        $this->privateContext = $privateContext;
    }

    /**
     * Return a string representation of the classes logic as a piece of PHP code.
     * Used to transfer important logic into generated code
     *
     * @return string
     */
    public function toCode()
    {
        $code = 'if ('. $this->getInvertString() .') {
                ' . ReservedKeywords::FAILURE_VARIABLE . '[] = \'The assertion (' . str_replace('\'', '"', $this->getString()) . ') must hold\';
            }';

        return $code;
    }
}
