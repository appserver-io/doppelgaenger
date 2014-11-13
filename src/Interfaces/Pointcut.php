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
 * @category   Appserver
 * @package    Doppelgaenger
 * @subpackage Interfaces
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Interfaces;

/**
 * AppserverIo\Doppelgaenger\Interfaces\Pointcut
 *
 * Interface which describes common pointcut functionality
 *
 * @category   Appserver
 * @package    Doppelgaenger
 * @subpackage Interfaces
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
interface Pointcut
{
    /**
     * Returns a string representing a boolean condition which can be used to determine if
     * the pointcut has to be executed
     *
     * @return string
     */
    public function getConditionString();

    /**
     * Returns a string representing the actual execution of the pointcut logic
     *
     * @return string
     */
    public function getExecutionString();

    /**
     * Getter for the expression property
     *
     * @return string
     */
    public function getExpression();

    /**
     * Returns the pattern which is used to match and define this pointcut
     *
     * @return string
     *
     * @Enum({"Signature", "TypePattern", "Expression", "Type", "Pointcut"})
     */
    public function getMatchPattern();

    /**
     * Getter for the type property
     *
     * @return string
     */
    public function getType();

    /**
     * Whether or not the pointcut match has to be negated in its result
     *
     * @return boolean
     */
    public function isNegated();

    /**
     * Whether or not the pointcut is considered static, meaning is has to be weaved and evaluated during runtime
     * anyway
     *
     * @return boolean
     */
    public function isStatic();

    /**
     * Whether or not the pointcut matches a given candidate
     *
     * @param mixed $candidate Candidate to match against the pointcuts match pattern (getMatchPattern() for information)
     *
     * @return boolean
     */
    public function matches($candidate);
}
