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
 * @category  Library
 * @package   Doppelgaenger
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2014 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger;

use AppserverIo\Doppelgaenger\Entities\Lists\AbstractTypedList;

/**
 * AppserverIo\Doppelgaenger\AspectRegister
 *
 * Class which knows about registered aspects to allow for checks of methods against all given pointcuts
 *
 * @category  Library
 * @package   Doppelgaenger
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2014 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.appserver.io/
 */
class AspectRegister extends AbstractTypedList
{

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->itemType = '\AppserverIo\Doppelgaenger\Entities\Aspect';
        $this->defaultOffset = 'name';
    }

    /**
     * @param $adviceName
     */
    public function lookupAdvice($adviceName)
    {
        throw new \Exception(__METHOD__ . ' not implemented yet');
    }

    /**
     * Will narrow down the choice of aspects to make when looking up pointcuts or advices.
     * Just pass the expression to look up any of both
     *
     * @param string $expression
     *
     * @return array|\AppserverIo\Doppelgaenger\Entities\Aspect
     */
    public function lookupAspects($expression)
    {
        return $this->lookupEntries($this->container, $expression);
    }

    /**
     * @param $container
     * @param $expression
     * @return array
     */
    protected function lookupEntries($container, $expression)
    {
        // if we got the complete name of the aspect we can return it alone
        if (!strpos($expression, '*') && $this->entryExists($expression)) {

            return $this->get($expression);
        }

        // as it seems we got something else we have to get all regex about
        $matches = array();
        foreach ($container as $entry) {

            if (preg_match('`' . $expression . '`', $entry->getQualifiedName()) === 1) {

                $matches[] = $entry;
            }
        }

        return $matches;
    }

    /**
     * @param $expression
     * @return array
     */
    public function lookupPointcuts($expression)
    {
        // if there is an aspect name within the expression we have to filter our search range and cut the expression
        $container = $this->container;
        if (strpos($expression, '->')) {

            $aspectExpression = strstr($expression, '->', true);
            $container = $this->lookupAspects($aspectExpression);
            $expression = str_replace('->' ,'', strstr($expression, '->'));
        }

        $matches = array();
        foreach ($container as $aspect) {

            $matches = array_merge($matches, $this->lookupEntries($aspect->getPointcuts(), $expression));
        }

        return $matches;
    }
}
