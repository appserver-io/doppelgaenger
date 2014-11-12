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
 * @package    AppserverIo_Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Entities\Pointcuts;

use AppserverIo\Doppelgaenger\Dictionaries\PointcutPatterns;

/**
 * AppserverIo\Doppelgaenger\Entities\Pointcuts\AbstractCombinatorPointcut
 *
 * Abstract type of pointcuts which are used to logically combine other pointcuts
 *
 * @category   Appserver
 * @package    AppserverIo_Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
abstract class AbstractConnectorPointcut extends AbstractPointcut
{

    /**
     * Call type for a call from an object
     *
     * @var string CONNECTOR_AND
     */
    const CONNECTOR_AND = '&&';

    /**
     * Call type for a static call
     *
     * @var string CONNECTOR_OR
     */
    const CONNECTOR_OR = '||';

    /**
     * The pattern used by this pointcut to match candidates
     *
     * @var string MATCH_PATTERN
     */
    const MATCH_PATTERN = PointcutPatterns::POINTCUT;

    /**
     * Pointcut specified to the left of the connector
     *
     * @var \AppserverIo\Doppelgaenger\Interfaces\Pointcut $leftPointcut
     */
    protected $leftPointcut;

    /**
     * Pointcut specified to the right of the connector
     *
     * @var \AppserverIo\Doppelgaenger\Interfaces\Pointcut $rightPointcut
     */
    protected $rightPointcut;

    /**
     * Default constructor
     *
     * @param string  $expression String representing the expression defining this pointcut
     * @param boolean $isNegated  If any match made against this pointcut's expression has to be negated in its result
     */
    public function __construct($expression, $isNegated)
    {
        parent::__construct($expression, $isNegated);

        //TODO
    }

    /**
     * Returns a string representing a boolean condition which can be used to determine if
     * the pointcut has to be executed
     *
     * @return string
     */
    public function getConditionString()
    {
        return $this->leftPointcut->getConditionString() . $this->getConnector().
            $this->rightPointcut->getConditionString();
    }

    /**
     * Getter for the pointcut specific connector
     *
     * @return string
     */
    protected function getConnector()
    {
        return self::CONNECTOR;
    }

    /**
     * Returns a string representing the actual execution of the pointcut logic
     *
     * @return string
     */
    public function getExecutionString()
    {
        return $this->leftPointcut->getExecutionString() . '
        ' . $this->rightPointcut->getExecutionString();
    }
}