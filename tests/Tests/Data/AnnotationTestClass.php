<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\AnnotationTestClass
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

namespace AppserverIo\Doppelgaenger\Tests\Data;

/**
 * Pointcut for specifying functions into which a certain advice has to be weaved.
 * Can only be used with a qualified method signature e.g. \AppserverIo\Doppelgaenger\Logger->log()
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class AnnotationTestClass
{
    /**
     * @param string[] $value
     */
    public function simpleTypeCollection($value)
    {

    }

    /**
     * @param \Exception[] $value
     */
    public function typeCollection($value)
    {

    }

    /**
     * @param array<\Exception>   $value
     */
    public function typeCollectionAlternative($value)
    {

    }

    /**
     * @return \Exception[]
     */
    public function typeCollectionReturn()
    {
        return array(new \Exception(), new \Exception(), new \Exception());
    }

    /**
     * @return \Exception[]
     */
    public function typeCollectionReturnFail()
    {
        return array(new \Exception(), new \stdClass(), new \Exception());
    }

    /**
     * @return array<\Exception>
     */
    public function typeCollectionAlternativeReturn()
    {
        return array(new \Exception(), new \Exception(), new \Exception());
    }

    /**
     * @return array<\Exception>
     */
    public function typeCollectionAlternativeReturnFail()
    {
        return array(new \Exception(), new \Exception(), new \stdClass());
    }

    /**
     * @return integer[]
     */
    public function simpleTypeCollectionReturn()
    {
        return array(1, 2, 3);
    }

    /**
     * @return string[]
     */
    public function simpleTypeCollectionReturnFail()
    {
        return array(1, 'wad', 'awd');
    }

    /**
     * @param null|\Exception|string $value
     */
    public function orCombinator($value)
    {

    }

    /**
     * @param
     *          $param1
     */
    private function iHaveBadAnnotations($param1)
    {

    }
}
