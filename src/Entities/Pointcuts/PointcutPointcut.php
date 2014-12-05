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
 * AppserverIo\Doppelgaenger\Entities\Pointcut\PointcutPointcut
 *
 * Pointcut expression for specifying a collection of other pointcuts and annotations expressing them
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 *
 * @Target({"ADVICE"})
 */
class PointcutPointcut extends AbstractPointcut
{

    /**
     * Connector for referencing of several pointcuts at once
     *
     * @var string ADD_CONNECTOR
     */
    const ADD_CONNECTOR = '&&';

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
    const TYPE = 'pointcut';

    /**
     * Pointcuts referenced by this pointcut's expression
     *
     * @var array $referencedPointcuts
     */
    protected $referencedPointcuts;

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
     * Getter for the $referencedPointcuts property
     *
     * @return array
     */
    public function getReferencedPointcuts()
    {
        return $this->referencedPointcuts;
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

        // iterate all referenced pointcuts and return true if one of them matches
        foreach ($this->referencedPointcuts as $referencedPointcut) {

            if ($referencedPointcut->pointcutExpression->getPointcut()->matches($candidate)) {

                return true;
            }
        }

        return false;
    }
}
