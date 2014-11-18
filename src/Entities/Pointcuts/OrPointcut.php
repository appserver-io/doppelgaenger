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
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Entities\Pointcuts;

/**
 * AppserverIo\Doppelgaenger\Entities\Pointcuts\OrPointcut
 *
 * Pointcut to or-connect two other pointcuts logically
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 *
 * @Target({"ADVICE", "METHOD","PROPERTY"})
 */
class OrPointcut extends AbstractConnectorPointcut
{
    /**
     * Connector which connects two pointcuts in a logical manner
     *
     * @var string CONNECTOR
     */
    const CONNECTOR = self::CONNECTOR_OR;

    /**
     * Returns a string representing a boolean condition which can be used to determine if
     * the pointcut has to be executed
     *
     * @return string
     */
    public function getConditionString()
    {
        // if one of these conditions is true we can default to 'true'
        if ($this->leftPointcut->getConditionString() === 'true' || $this->rightPointcut->getConditionString() === 'true') {

            return 'true';

        } else {

            return '(' . $this->leftPointcut->getConditionString() . $this->getConnector().
            $this->rightPointcut->getConditionString() . ')';
        }
    }

    /**
     * Whether or not the pointcut matches a given candidate.
     * For connector pointcuts this mostly depends on the connected pointcuts
     *
     * @param mixed $candidate Candidate to match against the pointcuts match pattern (getMatchPattern())
     *
     * @return boolean
     */
    public function matches($candidate)
    {
        return ($this->leftPointcut->matches($candidate) || $this->rightPointcut->matches($candidate));
    }
}
