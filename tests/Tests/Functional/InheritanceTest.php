<?php
/**
 * File containing the InheritanceTest class
 *
 * PHP version 5
 *
 * @category   Php-by-contract
 * @package    AppserverIo\Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php
 *             Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Tests\Functional;

use AppserverIo\Doppelgaenger\Tests\Data\ChildTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\BasicChildTestClass;

/**
 * AppserverIo\Doppelgaenger\Tests\Functional\InheritanceTest
 *
 * This test covers issues with inheritance of contracts
 *
 * @category   Php-by-contract
 * @package    AppserverIo\Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php
 *             Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class InheritanceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Will test if inheritance of precondition works over one level (class to class)
     *
     * @return null
     */
    public function testInheritance()
    {
        $testClass = new ChildTestClass();


        // These should fail
        $e = null;
        try {

            $testClass->pop();

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertInstanceOf("TechDivision\\PBC\\Exceptions\\BrokenPreconditionException", $e);

    }

    /**
     * Will test if inheritance works with overwritten methods having a different signature as the parent methods
     *
     * @return null
     */
    public function testChangedSignature()
    {
        $level = error_reporting();
        error_reporting(0);

        $testClass = new BasicChildTestClass();

        // Reset the error reporting level to the original value
        error_reporting($level);
        // These should not fail
        $e = null;
        try {

            $testClass->concatSomeStuff(12, 'test');

        } catch (\Exception $e) {
        }

        // Did we get null?
        $this->assertNull($e);

        // These should not fail as well
        $e = null;
        try {

            $testClass->stringToArray('this is a ', 'test');

        } catch (\Exception $e) {
        }

        // Did we get null?
        $this->assertNull($e);
    }
}
