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
 * AppserverIo\Doppelgaenger\Interfaces\PointcutInterface
 *
 * Interface which describes common pointcut functionality
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Interfaces
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
interface PointcutInterface
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
     * @param string|null $assignTo Should the result be assigned and stored for later use? If so, to what?
     *
     * @return string
     */
    public function getExecutionString($assignTo = null);

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
