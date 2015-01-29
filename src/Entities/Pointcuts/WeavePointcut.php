<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Pointcut\WeavePointcut
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

namespace AppserverIo\Doppelgaenger\Entities\Pointcuts;

/**
 * Pointcut for direct weaving of advice logic.
 * Can only be used with a qualified method signature e.g. \AppserverIo\Doppelgaenger\Logger->log(__METHOD__)
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 *
 * @Target({"METHOD","PROPERTY"})
 */
class WeavePointcut extends AbstractSignaturePointcut
{

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
    const TYPE = 'weave';

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
        $assignmentPrefix = '';
        if (!is_null($assignTo)) {
            $assignmentPrefix = $assignTo . ' = ';
        }

        // we have to test whether or not we need an instance of the used class first.
        // if the call is not static then we do
        $string = '';
        $expression = $this->getExpression();
        if ($this->callType === self::CALL_TYPE_OBJECT) {
            // don't forget to create an instance first
            $variable = '$' . lcfirst(str_replace('\\', '', $this->structure));
            $string .= $variable . ' = new ' . $this->structure . '();
            ';
            $string .= $assignmentPrefix . $variable . $this->callType . $this->function . ';
            ';

        } else {
            $string .= $assignmentPrefix . $expression . ';
            ';
        }

        return $string;
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
        return true;
    }
}
