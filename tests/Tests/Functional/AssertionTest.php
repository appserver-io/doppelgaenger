<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Functional\AssertionTest
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

namespace AppserverIo\Doppelgaenger\Tests\Functional;

use AppserverIo\Doppelgaenger\Tests\Data\AssertionTest\RespectValidationTestClass;
use \AppserverIo\Psr\MetaobjectProtocol\Dbc\ContractExceptionInterface;
use Herrera\Annotations\Convert\ToArray;
use Herrera\Annotations\Tokenizer;
use Herrera\Annotations\Tokens;

/**
 * Tests if our various validation types work as expected
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class AssertionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Instance of our test class
     *
     * @var \AppserverIo\Doppelgaenger\Tests\Data\AssertionTest\RespectValidationTestClass $testClass
     */
    protected $testClass;

    /**
     * Get our test class
     *
     * @return null
     */
    public function setUp()
    {
        $this->testClass = new RespectValidationTestClass();
    }

    /**
     * Tests if we can handle single require checks consisting of scalar type checks.
     * Tests the respect/validation assertion type
     *
     * @return null
     */
    public function testRespectValidationSingleScalarRequireMessage()
    {
        $this->testClass->iRequireOneInteger(1);

        try {
            $this->testClass->iRequireOneInteger('not an integer');

        } catch (ContractExceptionInterface $e) {

            $this->assertNotFalse(strpos($e->getMessage(), '"not an integer"'));
            $this->assertFalse(strpos($e->getMessage(), 'Failed'));
            return;
        }

        $this->fail();
    }

    /**
     * Tests if we can handle multiple require checks consisting of scalar type checks.
     * Tests the respect/validation assertion type
     *
     * @return null
     */
    public function testRespectValidationMultipleScalarRequireMessage()
    {
        $this->testClass->iRequireTwoStrings('string1', 'string2');

        try {
            $this->testClass->iRequireTwoStrings(1, 'string');

        } catch (ContractExceptionInterface $e) {

            $this->assertNotFalse(strpos($e->getMessage(), '1 must be a string'));
            $this->assertFalse(strpos($e->getMessage(), 'Failed'));
            return;
        }

        $this->fail();
    }

    /**
     * Tests if we can handle multiple require checks consisting of scalar type checks.
     * Tests the respect/validation assertion type
     *
     * @return null
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPreconditionException
     */
    public function testRespectValidationMultipleScalarRequireWorks1()
    {
        $this->testClass->iRequireTwoStrings(1, 'string');
    }

    /**
     * Tests if we can handle multiple require checks consisting of scalar type checks.
     * Tests the respect/validation assertion type
     *
     * @return null
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPreconditionException
     */
    public function testRespectValidationMultipleScalarRequireWorks2()
    {
        $this->testClass->iRequireTwoStrings('string', 1);
    }

    /**
     * Tests if we can handle a single invariant check consisting of complex type checks.
     * Tests the respect/validation assertion type
     *
     * @return null
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenInvariantException
     */
    public function testRespectValidationSingleComplexInvariantWorks()
    {
        $iHaveTwoProperties = new \stdClass();
        $iHaveTwoProperties->name = 'iHaveTwoProperties';
        $iHaveTwoProperties->birthday = '2015-01-01';
        $this->testClass->iHaveTwoProperties = $iHaveTwoProperties;
    }

    /**
     * Tests if we can handle multiple require checks consisting of scalar type checks.
     * Tests the respect/validation assertion type
     *
     * @return null
     */
    public function testRespectValidationSingleComplexInvariantMessage()
    {
        $iHaveTwoProperties = new \stdClass();
        $iHaveTwoProperties->name = 'iHaveTwoProperties';
        $iHaveTwoProperties->birthday = '1957-07-01';
        $this->testClass->iHaveTwoProperties = $iHaveTwoProperties;

        try {
            $iHaveTwoProperties->birthday = '2015-01-01';
            $this->testClass->iHaveTwoProperties = $iHaveTwoProperties;

        } catch (ContractExceptionInterface $e) {
            //$this->assertNotFalse(strpos($e->getMessage(), 'The age must be 50 years or more')); TODO test for correct error message
            $this->assertFalse(strpos($e->getMessage(), 'Failed'));
            return;
        }

        $this->fail();
    }

    /**
     * Tests if we can handle single ensure checks consisting of scalar type checks.
     * Tests the respect/validation assertion type
     *
     * @return null
     */
    public function testRespectValidationSingleScalarEnsureMessage()
    {
        $this->testClass->iEnsureOneObjectAndSucceed();

        try {
            $this->testClass->iEnsureOneObjectAndFail();

        } catch (ContractExceptionInterface $e) {

            $this->assertNotFalse(strpos($e->getMessage(), 'null must be an object'));
            $this->assertFalse(strpos($e->getMessage(), 'Failed'));
            return;
        }

        $this->fail();
    }
}
