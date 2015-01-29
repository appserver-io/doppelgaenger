<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Pointcuts\AndPointcut
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
 * Pointcut to and-connect two other pointcuts logically
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 *
 * @Target({"ADVICE", "METHOD","PROPERTY"})
 */
class AndPointcut extends AbstractConnectorPointcut
{
    /**
     * Connector which connects two pointcuts in a logical manner
     *
     * @var string CONNECTOR
     */
    const CONNECTOR = self::CONNECTOR_AND;

    /**
     * The type of this pointcut
     *
     * @var string TYPE
     */
    const TYPE = 'and';

    /**
     * Returns a string representing a boolean condition which can be used to determine if
     * the pointcut has to be executed
     *
     * @return string
     */
    public function getConditionString()
    {
        // we have to check if any of these conditions can be omitted in terms of boolean algebra
        if ($this->leftPointcut->getConditionString() === 'true' && $this->rightPointcut->getConditionString() === 'true') {
            return 'true';

        } elseif ($this->leftPointcut->getConditionString() === 'true') {
            return $this->rightPointcut->getConditionString();

        } elseif ($this->rightPointcut->getConditionString() === 'true') {
            return $this->leftPointcut->getConditionString();

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
        return ($this->leftPointcut->matches($candidate) && $this->rightPointcut->matches($candidate));
    }
}
