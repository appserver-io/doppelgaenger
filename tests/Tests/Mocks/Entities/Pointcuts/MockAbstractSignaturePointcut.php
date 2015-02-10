<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Mocks\Entities\Pointcuts\MockAbstractSignaturePointcut
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

namespace AppserverIo\Doppelgaenger\Tests\Mocks\Entities\Pointcuts;

use AppserverIo\Doppelgaenger\Entities\Pointcuts\AbstractSignaturePointcut;

/**
 * Unit testing AbstractSignaturePointcutTest
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class MockAbstractSignaturePointcut extends AbstractSignaturePointcut
{

    /**
     * The type of the call made to $function
     *
     * @var string|null $callType
     *
     * @Enum({"->", "::", null})
     */
    public $callType;

    /**
     * Raw expression as defined within code
     *
     * @var string $expression
     */
    public $expression;

    /**
     * Function/method which will get called within the signature expression
     *
     * @var string $function
     */
    public $function;

    /**
     * Structure name (if any) of the structure the called method belongs to
     *
     * @var string|null $structure
     */
    public $structure;

    /**
     * Returns a string representing a boolean condition which can be used to determine if
     * the pointcut has to be executed
     *
     * @return string
     */
    public function getConditionString()
    {
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
    }
}
