<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Pointcuts\PointcutFactory
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

use AppserverIo\Doppelgaenger\Utils\Parser;

/**
 * Factory which will produce instances of specific pointcut classes based on their type and expression
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class PointcutFactory
{

    /**
     * Will look for a logically balanced connector pointcut of a certain type
     *
     * @param string $expression String to be analysed for potential connector pointcut use
     * @param string $class      Type of connector pointcut to look for
     *
     * @return boolean|\AppserverIo\Doppelgaenger\Interfaces\PointcutInterface
     */
    protected function findConnectorPointcut($expression, $class)
    {
        // break up the string at the defined connectors and check for the bracket count on each side.
        // an even bracket count on both sides means we found the outermost connection
        $connector = constant($class . '::CONNECTOR');
        if (strpos($expression, $connector) !== false) {
            $connectorCount = substr_count($expression, $connector);
            $connectionIndex = 0;

            // get a parsing helper and analyze bracket counts to determine where we are at
            $parserUtil = new Parser();
            for ($i = 0; $i < $connectorCount; $i++) {
                $connectionIndex = strpos($expression, $connector, $connectionIndex + 1);
                $leftCandidate = substr($expression, 0, $connectionIndex);
                $rightCandidate = str_replace($leftCandidate . $connector, '', $expression);

                $leftBrackets = $parserUtil->getBracketCount($leftCandidate, '(');
                if ($leftBrackets === 0 && !empty($leftCandidate)) {
                    if ($parserUtil->getBracketCount($rightCandidate, '(') === 0 && !empty($rightCandidate)) {
                        return new $class($leftCandidate, $rightCandidate);
                    }

                }
            }
        }

        // if we arrived here we did not find anything
        return false;
    }

    /**
     * Will return an instance of an AbstractPointcut based on the given expression
     *
     * @param string $expression Expression specifying a certain pointcut
     *
     * @return \AppserverIo\Doppelgaenger\Interfaces\PointcutInterface
     *
     * @throws \InvalidArgumentException
     */
    public function getInstance($expression)
    {
        // might be a simple type of pointcut
        $isNegated = false;

        // there are advices which do not reference any pointcuts, spare them the parsing
        if (empty($expression)) {
            $type = 'blank';

        } else {
            // first of all we have to get the type of the pointcut
            // check for connector pointcuts first
            $expression = trim($expression);

            // if we are already in a wrapping connector pointcut then we will cut it off as those are not distinguished
            // by type but rather by their connector
            $expression = $this->trimConnectorTypes($expression);

            // now lets have a look if we are wrapped in some outer brackets
            $parserUtil = new Parser();
            if (strlen($expression) === $parserUtil->getBracketSpan($expression, '(')) {
                $expression = substr($expression, 1, strlen($expression) - 2);
            }

            // now check if we do have any "and" connectors here
            if (strpos($expression, AndPointcut::CONNECTOR) !== false) {
                $class = '\AppserverIo\Doppelgaenger\Entities\Pointcuts\AndPointcut';
                $tmp = $this->findConnectorPointcut($expression, $class);
                if ($tmp !== false) {
                    return $tmp;
                }
            }

            // or-connection comes second
            if (strpos($expression, OrPointcut::CONNECTOR) !== false) {
                $class = '\AppserverIo\Doppelgaenger\Entities\Pointcuts\OrPointcut';
                $tmp = $this->findConnectorPointcut($expression, $class);
                if ($tmp !== false) {
                    return $tmp;
                }
            }

            // trim the expression from containing brackets first
            while ($expression[0] === '(' && $expression[strlen($expression) - 1] === ')') {
                $expression = substr($expression, 1, strlen($expression) - 2);
            }

            if (strpos($expression, '!') !== false) {
                $isNegated = true;
                $expression = str_replace('!', '', $expression);
            }
            $type = trim(strstr($expression, '(', true));
        }

        // build up the class name and check if we know a class like that
        $class = '\AppserverIo\Doppelgaenger\Entities\Pointcuts\\' . ucfirst($type) . 'Pointcut';

        // check if we got a valid class
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Could not resolve the expression %s to any known pointcut type', $expression));
        }

        $pointcut = new $class(substr(trim(str_replace($type, '', $expression), '( '), 0, -1), $isNegated);

        return $pointcut;
    }

    /**
     * Will cut of any connector pointcut type as they are distinguished by connector rather than type
     *
     * @param string $expression The pointcut expression to trim
     *
     * @return string
     */
    protected function trimConnectorTypes($expression)
    {
        if (strpos($expression, AndPointcut::TYPE) === 0) {
            $expression = str_replace(AndPointcut::TYPE, '', $expression);

        } elseif (strpos($expression, OrPointcut::TYPE) === 0) {
            $expression = str_replace(OrPointcut::TYPE, '', $expression);
        }

        return $expression;
    }
}
