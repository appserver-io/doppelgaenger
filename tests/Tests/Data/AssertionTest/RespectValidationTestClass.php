<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\AssertionTest\RespectValidationTestClass
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

namespace AppserverIo\Doppelgaenger\Tests\Data\AssertionTest;

use Respect\Validation\Validator as v;

/**
 * Class used to test the functionality of the RespectValidation assertion type
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 *
 * @Invariant(
 *    type="RespectValidation",
 *    constraint="v::attribute('name', v::stringType()->length(1,32))->attribute('birthday', v::age(50))->assert($this->iHaveTwoProperties)"
 * )
 */
class RespectValidationTestClass
{

    /**
     * An "user" we can test complex assertions on
     *
     * @var \stdClass $iHaveTwoProperties
     */
    public $iHaveTwoProperties;

    /**
     * Default constructor
     */
    public function __construct()
    {
        $iHaveTwoProperties = new \stdClass();
        $iHaveTwoProperties->name = 'iHaveTwoProperties';
        $iHaveTwoProperties->birthday = '1957-07-01';
        $this->iHaveTwoProperties = $iHaveTwoProperties;
    }

    /**
     * @Requires(type="RespectValidation", constraint="v::intType()->check($value)")
     */
    public function iRequireOneInteger($value)
    {

    }

    /**
     * @Requires(type="RespectValidation", constraint="v::objectType()->check($value)")
     */
    public function iRequireOneObject($value)
    {

    }

    /**
     * @Requires(type="RespectValidation", constraint="v::stringType()->check($value1)")
     * @Requires(type="RespectValidation", constraint="v::stringType()->check($value2)")
     */
    public function iRequireTwoStrings($value1, $value2)
    {

    }

    /**
     * @Ensures(type="RespectValidation", constraint="v::objectType()->check($dgResult)")
     */
    public function iEnsureOneObjectAndFail()
    {

    }

    /**
     * @Ensures(type="RespectValidation", constraint="v::objectType()->check($dgResult)")
     */
    public function iEnsureOneObjectAndSucceed()
    {
        return new \stdClass();
    }

    /**
     * @Ensures(type="RespectValidation", constraint="v::stringType()->check($dgResult)")
     */
    public function iEnsureOneStringAndFail()
    {
        return 10;
    }

    /**
     * @Ensures(type="RespectValidation", constraint="v::stringType()->check($dgResult)")
     */
    public function iEnsureOneStringAndSucceed()
    {
        return 'string';
    }
}
