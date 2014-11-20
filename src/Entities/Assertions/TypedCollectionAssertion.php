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
 * AppserverIo\Doppelgaenger\Entities\Assertions\TypedCollectionAssertion
 *
 * Provides the option to check "collections" of the form array<Type>
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
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
        $code = 'count(array_filter(' . $this->operand . ', function(&$value) {
        if (!$value instanceof ' . $this->type . ') {

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
