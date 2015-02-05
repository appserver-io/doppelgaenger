<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Pointcut\AbstractPointcut
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

use AppserverIo\Doppelgaenger\Interfaces\PointcutInterface;
use AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition;
use AppserverIo\Doppelgaenger\Entities\Definitions\AttributeDefinition;

/**
 * Definition of a pointcut as a combination of a join-point and advices
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 *
 * @see        https://www.eclipse.org/aspectj/doc/next/progguide/quick.html
 * @see        https://www.eclipse.org/aspectj/doc/next/progguide/semantics-pointcuts.html
 *
 * @property string  $expression Raw expression as defined within code
 * @property boolean $isNegated  Has the result of any match to be negated?
 */
abstract class AbstractPointcut implements PointcutInterface
{

    /**
     * Raw expression as defined within code
     *
     * @var string $expression
     */
    protected $expression;

    /**
     * Has the result of any match to be negated?
     *
     * @var boolean $isNegated
     */
    protected $isNegated;

    /**
     * Default constructor
     *
     * @param string  $expression String representing the expression defining this pointcut
     * @param boolean $isNegated  If any match made against this pointcut's expression has to be negated in its result
     */
    public function __construct($expression, $isNegated = false)
    {
        $this->expression = $expression;
        $this->isNegated = $isNegated;
    }

    /**
     * Will return a chain of callbacks which can be used to call woven code in an onion like manner
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition $functionDefinition Definition of the function to inject invocation code into
     *
     * @return array
     */
    public function getCallbackChain(FunctionDefinition $functionDefinition)
    {
        return array();
    }

    /**
     * Getter for the expression property
     *
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * Returns the pattern which is used to match and define this pointcut
     *
     * @return string
     *
     * @Enum({"Signature", "TypePattern", "Expression", "Type", "Pointcut"})
     */
    public function getMatchPattern()
    {
        return static::MATCH_PATTERN;
    }

    /**
     * Getter for the type property
     *
     * @return string
     */
    public function getType()
    {
        return static::TYPE;
    }

    /**
     * Whether or not the pointcut is considered static, meaning is has to be weaved and evaluated during runtime
     * anyway
     *
     * @return boolean
     */
    public function isStatic()
    {
        return static::IS_STATIC;
    }

    /**
     * Whether or not the pointcut match has to be negated in its result
     *
     * @return boolean
     */
    public function isNegated()
    {
        return $this->isNegated;
    }

    /**
     * Used to "straighten out" an expression as some expressions allow for shell regex which makes them hard to
     * generate code from.
     * So with this method a matching pointcut can be altered into having a directly readable expression
     *
     * @param FunctionDefinition|AttributeDefinition $definition Definition to straighten the expression against
     *
     * @return null
     */
    public function straightenExpression($definition)
    {
        // nothing to do in general
    }
}
