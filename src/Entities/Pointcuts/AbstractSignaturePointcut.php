<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Pointcuts\AbstractSignaturePointcut
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

use AppserverIo\Doppelgaenger\Dictionaries\PointcutPatterns;
use AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition;
use AppserverIo\Doppelgaenger\Entities\Definitions\AttributeDefinition;

/**
 * Abstract parent class for pointcuts which accept expressions which express a signature like pattern
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
abstract class AbstractSignaturePointcut extends AbstractPointcut
{

    /**
     * Call type for a call from an object
     *
     * @var string CALL_TYPE_OBJECT
     */
    const CALL_TYPE_OBJECT = '->';

    /**
     * Call type for a static call
     *
     * @var string CALL_TYPE_STATIC
     */
    const CALL_TYPE_STATIC = '::';

    /**
     * The pattern used by this pointcut to match candidates
     *
     * @var string MATCH_PATTERN
     */
    const MATCH_PATTERN = PointcutPatterns::SIGNATURE;

    /**
     * The type of the call made to $function
     *
     * @var string|null $callType
     *
     * @Enum({"->", "::", null})
     */
    protected $callType;

    /**
     * Function/method which will get called within the signature expression
     *
     * @var string $function
     */
    protected $function;

    /**
     * Structure name (if any) of the structure the called method belongs to
     *
     * @var string|null $structure
     */
    protected $structure;

    /**
     * Default constructor
     *
     * @param string  $expression String representing the expression defining this pointcut
     * @param boolean $isNegated  If any match made against this pointcut's expression has to be negated in its result
     */
    public function __construct($expression, $isNegated)
    {
        parent::__construct($expression, $isNegated);

        // filter what we need
        if (strpos($expression, self::CALL_TYPE_OBJECT) !== false || strpos($expression, self::CALL_TYPE_STATIC) !== false) {
            // assume an object call but correct the call type in the unlikely case we did get a static call
            $this->callType = self::CALL_TYPE_OBJECT;
            if (strpos($expression, self::CALL_TYPE_STATIC) !== false) {
                $this->callType = self::CALL_TYPE_STATIC;
            }

            // we have to isolate the parts of the expression
            $this->structure = strstr($expression, $this->callType, true);
            $this->function = str_replace($this->structure . $this->callType, '', $expression);

        } else {
            $this->callType = null;
            $this->structure = null;
            $this->function = null;
        }
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

        if ($this->callType === self::CALL_TYPE_STATIC) {
            // we can work with the structure name alone if we have a static call

            return array(array($this->structure, $this->function));

        } elseif (ltrim($this->structure, '\\') === $functionDefinition->getStructureName()) {
            // if the callback chain is used within the actual class we can use the current context

            return array(array('$this', $this->function));

        } else {
            // for everything else (mostly advice chain callbacks) we will create a new instance

            return array(array('new ' . $this->structure . '()', $this->function));
        }
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
        // structure name has to be absolute
        $structureName = '\\' . ltrim($definition->getStructureName(), '\\');

        // fix the expression
        $this->expression = str_replace(
            array($this->callType . $this->function, $this->structure),
            array($this->callType . $definition->getName(), $structureName),
            $this->getExpression()
        );

        // set the obvious properties
        $this->function = $definition->getName();
        $this->structure = $structureName;
    }
}
