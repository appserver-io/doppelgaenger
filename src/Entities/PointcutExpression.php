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

namespace AppserverIo\Doppelgaenger\Entities;

use AppserverIo\Doppelgaenger\Dictionaries\ReservedKeywords;
use AppserverIo\Doppelgaenger\Entities\Annotations\Joinpoints\Around;
use AppserverIo\Doppelgaenger\Entities\Pointcuts\PointcutFactory;

/**
 * AppserverIo\Doppelgaenger\Entities\Pointcut
 *
 * Definition of a pointcut as a combination of a joinpoint and advices
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 *
 * @see        https://www.eclipse.org/aspectj/doc/next/progguide/quick.html
 * @see        https://www.eclipse.org/aspectj/doc/next/progguide/semantics-pointcuts.html
 */
class PointcutExpression extends AbstractLockableEntity
{

    /**
     * Joinpoint at which the enclosed advices have to be weaved
     *
     * @var \AppserverIo\Doppelgaenger\Entities\Joinpoint|null $joinpoint
     */
    protected $joinpoint;

    /**
     * Pointcut(tree) representing the logical structure of the given string expression
     *
     * @var \AppserverIo\Doppelgaenger\Interfaces\Pointcut $pointcut
     */
    protected $pointcut;

    /**
     * Original string definition of the pointcut
     *
     * @var string $string
     */
    protected $string;

    /**
     * Default constructor
     *
     * @param string $rawString Raw string the pointcuts expressions can be filtered from
     */
    public function __construct($rawString)
    {
        $this->joinpoint = null;
        $this->string = $rawString;

        $pointcutFactory = new PointcutFactory();
        $this->pointcut = $pointcutFactory->getInstance($rawString);
    }

    /**
     * Getter for the joinpoints property
     *
     * @return \AppserverIo\Doppelgaenger\Entities\Joinpoint|null
     */
    public function getJoinpoint()
    {
        return $this->joinpoint;
    }

    /**
     * Getter for the pointcut property
     *
     * @return \AppserverIo\Doppelgaenger\Interfaces\Pointcut
     */
    public function getPointcut()
    {
        return $this->pointcut;
    }

    /**
     * Return a string representation of the complete pointcut expression
     *
     * @return string
     */
    public function getString()
    {
        // around advices need to have their result saved
        $assignTo = null;
        if (is_null($this->getJoinpoint()) && $this->getJoinpoint()->codeHook === Around::ANNOTATION) {

            $assignTo = ReservedKeywords::RESULT;
        }

        // do we even have an useful condition?
        $condition = $this->getPointcut()->getConditionString();
        if ($condition === 'true' || empty($condition)) {

            return $this->getPointcut()->getExecutionString($assignTo);

        } else {

            return 'if (' . $condition .') {
            ' . $this->getPointcut()->getExecutionString($assignTo) . '
            }';
        }
    }
}
