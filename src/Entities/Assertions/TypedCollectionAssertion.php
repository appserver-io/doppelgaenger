<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Assertions\TypedCollectionAssertion
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
 * Provides the option to check "collections" of the form array<Type>
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class TypedCollectionAssertion extends AbstractAssertion
{
    /**
     * @var string $operand The operand to check
     */
    public $operand;

    /**
     * @var string $type The type are are checking against
     */
    public $type;

    /**
     * @var string $comparator Comparator, === by default
     */
    protected $comparator;

    /**
     * Default constructor
     *
     * @param string $operand The operand to check
     * @param string $type    The type are are checking against
     */
    public function __construct($operand, $type)
    {
        $this->operand = $operand;
        $this->type = $type;
        $this->comparator = '===';

        parent::__construct();
    }

    /**
     * Will return a string representation of this assertion
     *
     * @return string
     */
    public function getString()
    {
        // we want to know if we are looking for a class or a scalar type
        if (function_exists('is_' . $this->type)) {
            $validationString = 'is_' . $this->type . '($value)';

        } else {
            $validationString = '$value instanceof ' . $this->type;
        }

        // build up the check itself
        $code = 'count(array_filter(' . $this->operand . ', function(&$value) {
        if (!' . $validationString . ') {

            return true;
        }
        })) ' . $this->comparator . ' 0';

        return $code;
    }

    /**
     * Invert the logical meaning of this assertion
     *
     * @return bool
     */
    public function invert()
    {
        if ($this->inverted === false) {
            $this->comparator = '!==';
            $this->inverted = true;

            return true;

        } elseif ($this->inverted === true) {
            $this->comparator = '===';
            $this->inverted = false;

            return true;

        } else {
            return false;
        }
    }
}
