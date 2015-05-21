<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\ParserTest\ErrorLineTestClass
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

namespace AppserverIo\Doppelgaenger\Tests\Data\ParserTest;

/**
 * Test class for testing if lines of thrown errors are correct
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 *
 * @Processing("exception")
 *
 * @Invariant("$this->property === 'test'")
 */
class ErrorLineTestClass
{

    /**
     * Test property
     *
     * @var string $property
     */
    public $property = 'test';

    /**
     * Will always throw an error. Ecpected lines are 51 to 54
     *
     * @return string
     */
    public function iShouldFailAt54()
    {
        return false;
    }

    /**
     * Will always throw an error. Ecpected lines are 63 to 71
     *
     * @param string $array Will fail precondition
     *
     * @return string
     */
    public function iShouldFailAt63(array $array)
    {
        /**
         *
         */
        $test = 'filler';
        $array = array();
        return $test;
    }

    /**
     * Will always throw an error. Ecpected lines are 78 to 88
     *
     * @return string
     */
    public function iShouldFailAt88()
    {
        foreach (array() as $value) {
            $value = null;
        }

        return false;



    }

    /**
     * Will always throw an error. Ecpected lines are 95 to 101
     *
     * @return string
     */
    public function iShouldFailAt101()
    {
        /**
         *
         */
        $this->property = 'fail';
    }
}
