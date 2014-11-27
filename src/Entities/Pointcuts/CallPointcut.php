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

use AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition;

/**
 * AppserverIo\Doppelgaenger\Entities\Pointcut\CallPointcut
 *
 * Pointcut for specifying functions into which a certain advice has to be weaved.
 * Can only be used with a qualified method signature e.g. \AppserverIo\Doppelgaenger\Logger->log()
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 *
 * @Target({"POINTCUT"})
 */
class CallPointcut extends AbstractSignaturePointcut
{

    /**
     * Whether or not the pointcut is considered static, meaning is has to be weaved and evaluated during runtime
     * anyway
     *
     * @var boolean IS_STATIC
     */
    const IS_STATIC = false;

    /**
     * The type of this pointcut
     *
     * @var string TYPE
     */
    const TYPE = 'call';

    /**
     * Default constructor
     *
     * @param string  $expression String representing the expression defining this pointcut
     * @param boolean $isNegated  If any match made against this pointcut's expression has to be negated in its result
     */
    public function __construct($expression, $isNegated = false)
    {
        // clean any trailing brackets and proceed to parent constructor
        parent::__construct(rtrim($expression, '()'), $isNegated);
    }

    /**
     * Returns a string representing a boolean condition which can be used to determine if
     * the pointcut has to be executed
     *
     * @return string
     */
    public function getConditionString()
    {
        return 'true';
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
     * Weave pointcuts will always return true, as they do not pose any condition
     *
     * @param mixed $candidate Candidate to match against the pointcuts match pattern (getMatchPattern())
     *
     * @return boolean
     */
    public function matches($candidate)
    {
        // if we do not get a function definition we can already assume there is no match
        if (!$candidate instanceof FunctionDefinition) {

            return false;
        }

        // build up the signature of the candidate function definition and look for a match
        $candidateSignature = $candidate->getStructureName() . $this->callType . $candidate->getName();
        return fnmatch(str_replace('\\', '\\\\', ltrim($this->getExpression(), '\\')), $candidateSignature);
    }
}
