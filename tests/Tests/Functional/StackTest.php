<?php
/**
 * File containing the StackTest class
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

use AppserverIo\Doppelgaenger\Tests\Data\Stack\StackSale;

/**
 * AppserverIo\Doppelgaenger\Tests\Functional\StackTest
 *
 * Will test with the well known stack example
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
class StackTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Get the test and scoop around in the stack
     *
     * @return null
     */
    public function testBuild()
    {
        // Get the object to test
        $stackSale = new StackSale();
        $stackSale->sell();
    }
}
