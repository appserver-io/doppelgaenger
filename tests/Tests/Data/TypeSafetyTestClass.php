<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\TypeSafetyTestClass
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
 * Class used to test basic type safety
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class TypeSafetyTestClass
{

    /**
     * @param   string $string1
     * @param   string $string2
     */
    public function iNeedStrings($string1, $string2)
    {

    }

    /**
     * @param   array $array1
     * @param   array $array2
     */
    public function iNeedArrays($array1, $array2)
    {

    }

    /**
     * @param   numeric $numeric
     */
    public function iNeedNumeric($numeric)
    {

    }

    /**
     * @return string
     */
    public function iReturnAString($result = 'test')
    {
        return $result;
    }

    /**
     * @return array
     */
    public function iReturnAnArray($result = array('golem', 'clay'))
    {
        return $result;
    }

    /**
     * @return int
     */
    public function iReturnAnInt($result = 42)
    {
        return $result;
    }
}
