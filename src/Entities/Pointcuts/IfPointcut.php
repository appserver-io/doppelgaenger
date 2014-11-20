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

namespace AppserverIo\Doppelgaenger\Entities\Pointcuts;

use AppserverIo\Doppelgaenger\Dictionaries\PointcutPatterns;

/**
 * AppserverIo\Doppelgaenger\Entities\Pointcut\IfPointcut
 *
 * Pointcut for runtime checking of certain conditions.
 * Can be used as "if" condition on which the activation of other pointcuts might depend
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 *
 * @Target({"POINTCUT", "METHOD","PROPERTY"})
 */
class IfPointcut extends AbstractPointcut
{

    /**
     * The pattern used by this pointcut to match candidates
     *
     * @var string MATCH_PATTERN
     */
    const MATCH_PATTERN = PointcutPatterns::EXPRESSION;

    /**
     * Whether or not the pointcut is considered static, meaning is has to be weaved and evaluated during runtime
     * anyway
     *
     * @var boolean IS_STATIC
     */
    const IS_STATIC = true;

    /**
     * The type of this pointcut
     *
     * @var string TYPE
     */
    const TYPE = 'if';

    /**
     * Returns a string representing a boolean condition which can be used to determine if
     * the pointcut has to be executed
     *
     * @return string
     */
    public function getConditionString()
    {
        return '(' . $this->getExpression() . ')';
    }

    /**
     * Returns a string representing the actual execution of the pointcut logic
     *
     * @param string|null $assignTo Should the result be assigned and stored for later use? If so, to what?
     *
     * @return string
     */
    public function getExecutionString($assignTo = null)
    {
        return '';
    }

    /**
     * Whether or not the pointcut matches a given candidate.
     * If pointcuts will always return true as they have to be evaluated at runtime
     *
     * @param mixed $candidate Candidate to match against the pointcuts match pattern (getMatchPattern())
     *
     * @return boolean
     */
    public function matches($candidate)
    {
        return true;
    }
}
