<?php
/**
 * File containing the GeneratorTest class
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

use AppserverIo\Doppelgaenger\Tests\Data\TagPlacementTestClass;

/**
 * AppserverIo\Doppelgaenger\Tests\Functional\GeneratorTest
 *
 * This test covers known generator problems
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
class GeneratorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Will test if a randomly placed php tag will throw of the generator
     *
     * @return null
     */
    public function testPhpTag()
    {
        $e = null;
        try {

            $tagPlacementTestClass = new TagPlacementTestClass();

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertNull($e);
    }
}
