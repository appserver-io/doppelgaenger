<?php
/**
 * File containing the ParserTest class
 *
 * PHP version 5
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Tests\Functional;

use AppserverIo\Doppelgaenger\Config;
use AppserverIo\Doppelgaenger\Tests\Data\Annotations\ComplexTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\Annotations\MixedTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\Annotations\SeveralTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\Annotations\SingleIntroductionTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\Annotations\SeveralIntroductionsTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\Annotations\SingleTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\AnnotationTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\MethodTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\MultiRegex\A\Data\RegexTestClass1;
use AppserverIo\Doppelgaenger\Tests\Data\MultiRegex\B\Data\RegexTestClass2;
use AppserverIo\Doppelgaenger\Tests\Data\RegexTest1\RegexTestClass;

/**
 * AppserverIo\Doppelgaenger\Tests\Functional\ParserTest
 *
 * Will test basic parser usage
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Will test parsing of special annotations like typed arrays
     *
     * @return null
     */
    public function testAnnotationParsing()
    {
        // Get the object to test
        $annotationTestClass = new AnnotationTestClass();

        $e = null;
        try {

            $annotationTestClass->typeCollection(array(new \Exception(), new \Exception(), new \Exception()));

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertNull($e);

        // Get the object to test
        $annotationTestClass = new AnnotationTestClass();

        $e = null;
        try {

            $annotationTestClass->typeCollectionReturn(array(new \Exception(), new \Exception(), new \Exception()));

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertNull($e);

        $e = null;
        try {

            $annotationTestClass->orCombinator(new \Exception());
            $annotationTestClass->orCombinator(null);

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertNull($e);
    }

    /**
     * Will check for proper method parsing
     *
     * @return null
     */
    public function testMethodParsing()
    {
        $e = null;
        try {

            $methodTestClass = new MethodTestClass();

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertNull($e);
    }

    /**
     * Will test if a configuration using regexed paths can be used properly
     *
     * @return null
     */
    public function testRegexMapping()
    {
        // We have to load the config for regular expressions in the project dirs
        $config = new Config();
        $config->load(
            str_replace(DIRECTORY_SEPARATOR . 'Functional', '', __DIR__) . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'RegexTest' .
            DIRECTORY_SEPARATOR . 'regextest.conf.json'
        );

        $e = null;
        try {

            $regexTestClass1 = new RegexTestClass1();

        } catch (Exception $e) {
        }

        // Did we get the right $e?
        $this->assertNull($e);

        $e = null;
        try {

            $regexTestClass2 = new RegexTestClass2();

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertNull($e);

        $e = null;
        try {

            $regexTestClass = new RegexTestClass();

        } catch (\Exception $e) {
        }

        // Did we get the right $e?
        $this->assertNull($e);
    }

    /**
     * Will test if classes with methods which have a single advice each are processable
     *
     * @return null
     */
    public function testSingleDirectAdvices()
    {
        $test = new SingleTestClass();
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Tests\Data\Annotations\SingleTestClass', $test);
    }

    /**
     * Will test if classes with methods which have several advices each are processable
     *
     * @return null
     */
    public function testSeveralDirectAdvices()
    {
        $test = new SeveralTestClass();
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Tests\Data\Annotations\SeveralTestClass', $test);
    }

    /**
     * Will test if classes with methods which have advices with mixed joinpoints are processable
     *
     * @return null
     */
    public function testMixedDirectAdvices()
    {
        $test = new MixedTestClass();
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Tests\Data\Annotations\MixedTestClass', $test);
    }

    /**
     * Will test if classes with methods which have complex advices are processable
     *
     * @return null
     */
    public function testComplexDirectAdvices()
    {
        $test = new ComplexTestClass();
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Tests\Data\Annotations\ComplexTestClass', $test);
    }

    /**
     * Will test if classes with an introduction will get their characteristics extended correctly
     *
     * @return null
     */
    public function testSingleIntroduction()
    {
        $test = new SingleIntroductionTestClass();
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Tests\Data\Annotations\SingleIntroductionTestClass', $test);
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Tests\Data\Annotations\TestInterface1', $test);
    }

    /**
     * Will test if classes with several introductions will get their characteristics extended correctly
     *
     * @return null
     */
    public function testSeveralIntroductions()
    {
        $test = new SeveralIntroductionsTestClass();
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Tests\Data\Annotations\SeveralIntroductionsTestClass', $test);
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Tests\Data\Annotations\TestInterface1', $test);
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Tests\Data\Annotations\TestInterface2', $test);
    }
}
