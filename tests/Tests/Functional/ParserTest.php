<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Functional\ParserTest
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

use AppserverIo\Doppelgaenger\Config;
use AppserverIo\Doppelgaenger\Tests\Data\Annotations\ComplexTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\Annotations\ExtendedIntroductionTestClass;
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
use AppserverIo\Doppelgaenger\Tests\Data\ParserTest\MultiClassTokenTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\ParserTest\ErrorLineTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\ParserTest\BasicTestClass;

/**
 * Will test basic parser usage
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Will test parsing of special annotations like typed arrays
     *
     * @return void
     */
    public function testAnnotationParsing()
    {
        $annotationTestClass = new AnnotationTestClass();

        $annotationTestClass->orCombinator(new \Exception());
        $annotationTestClass->orCombinator(null);
    }

    /**
     * Will test parsing of special annotations like typed arrays
     *
     * @return void
     */
    public function testAnnotationParsingTypeCollectionParameter()
    {
        // Get the object to test
        $annotationTestClass = new AnnotationTestClass();
        $annotationTestClass->typeCollection(array(new \Exception(), new \Exception(), new \Exception()));
    }

    /**
     * Will test parsing of special annotations like typed arrays
     *
     * @return void
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPreconditionException
     */
    public function testAnnotationParsingTypeCollectionParameterFail()
    {
        // Get the object to test
        $annotationTestClass = new AnnotationTestClass();
        $annotationTestClass->typeCollection(array(new \Exception(), new \stdClass(), new \Exception()));
    }

    /**
     * Will test parsing of special annotations like typed arrays
     *
     * @return void
     */
    public function testAnnotationParsingTypeCollectionReturn()
    {
        // Get the object to test
        $annotationTestClass = new AnnotationTestClass();
        $annotationTestClass->typeCollectionReturn();
    }

    /**
     * Will test parsing of special annotations like typed arrays
     *
     * @return void
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPostconditionException
     */
    public function testAnnotationParsingTypeCollectionReturnFail()
    {
        // Get the object to test
        $annotationTestClass = new AnnotationTestClass();
        $annotationTestClass->typeCollectionReturnFail();
    }

    /**
     * Will test parsing of special annotations like typed arrays
     *
     * @return void
     */
    public function testAnnotationParsingTypeCollectionAlternativeParameter()
    {
        // Get the object to test
        $annotationTestClass = new AnnotationTestClass();
        $annotationTestClass->typeCollectionAlternative(array(new \Exception(), new \Exception(), new \Exception()));
    }

    /**
     * Will test parsing of special annotations like typed arrays
     *
     * @return void
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPreconditionException
     */
    public function testAnnotationParsingTypeCollectionAlternativeParameterFail()
    {
        // Get the object to test
        $annotationTestClass = new AnnotationTestClass();
        $annotationTestClass->typeCollectionAlternative(array(new \Exception(), new \Exception(), new \stdClass()));
    }

    /**
     * Will test parsing of special annotations like typed arrays
     *
     * @return void
     */
    public function testAnnotationParsingTypeCollectionAlternativeReturn()
    {
        // Get the object to test
        $annotationTestClass = new AnnotationTestClass();
        $annotationTestClass->typeCollectionAlternativeReturn();
    }

    /**
     * Will test parsing of special annotations like typed arrays
     *
     * @return void
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPostconditionException
     */
    public function testAnnotationParsingTypeCollectionAlternativeReturnFail()
    {
        // Get the object to test
        $annotationTestClass = new AnnotationTestClass();
        $annotationTestClass->typeCollectionAlternativeReturnFail();
    }

    /**
     * Will test parsing of special annotations like typed arrays
     *
     * @return void
     */
    public function testAnnotationParsingSimpleTypeCollectionParameter()
    {
        // Get the object to test
        $annotationTestClass = new AnnotationTestClass();
        $annotationTestClass->simpleTypeCollection(array('asd', 'wad', 'awd'));
    }

    /**
     * Will test parsing of special annotations like typed arrays
     *
     * @return void
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPreconditionException
     */
    public function testAnnotationParsingSimpleTypeCollectionParameterFail()
    {
        // Get the object to test
        $annotationTestClass = new AnnotationTestClass();
        $annotationTestClass->simpleTypeCollection(array('asd', new \stdClass(), 'awd'));
    }

    /**
     * Will test parsing of special annotations like typed arrays
     *
     * @return void
     */
    public function testAnnotationParsingSimpleTypeCollectionReturn()
    {
        // Get the object to test
        $annotationTestClass = new AnnotationTestClass();
        $annotationTestClass->simpleTypeCollectionReturn();
    }

    /**
     * Will test parsing of special annotations like typed arrays
     *
     * @return void
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPostconditionException
     */
    public function testAnnotationParsingSimpleTypeCollectionReturnFail()
    {
        // Get the object to test
        $annotationTestClass = new AnnotationTestClass();
        $annotationTestClass->simpleTypeCollectionReturnFail();
    }

    /**
     * Will check for proper method parsing
     *
     * @return void
     */
    public function testMethodParsingWithoutException()
    {
        $methodTestClass = new MethodTestClass();
    }

    /**
     * Will test if a configuration using regexed paths can be used properly
     *
     * @return void
     */
    public function testRegexMappingWithoutException()
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

        } catch (\Exception $e) {
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
     * @return void
     */
    public function testSingleDirectAdvices()
    {
        $test = new SingleTestClass();
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Tests\Data\Annotations\SingleTestClass', $test);
    }

    /**
     * Will test if classes with methods which have several advices each are processable
     *
     * @return void
     */
    public function testSeveralDirectAdvices()
    {
        $test = new SeveralTestClass();
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Tests\Data\Annotations\SeveralTestClass', $test);
    }

    /**
     * Will test if classes with methods which have advices with mixed joinpoints are processable
     *
     * @return void
     */
    public function testMixedDirectAdvices()
    {
        $test = new MixedTestClass();
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Tests\Data\Annotations\MixedTestClass', $test);
    }

    /**
     * Will test if classes with methods which have complex advices are processable
     *
     * @return void
     */
    public function testComplexDirectAdvices()
    {
        $test = new ComplexTestClass();
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Tests\Data\Annotations\ComplexTestClass', $test);
    }

    /**
     * Will test if classes with an introduction will get their characteristics extended correctly
     *
     * @return void
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
     * @return void
     */
    public function testSeveralIntroductions()
    {
        $test = new SeveralIntroductionsTestClass();
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Tests\Data\Annotations\SeveralIntroductionsTestClass', $test);
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Tests\Data\Annotations\TestInterface1', $test);
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Tests\Data\Annotations\TestInterface2', $test);
    }

    /**
     * Will test if classes with an introduction will get their characteristics extended correctly
     *
     * @return void
     */
    public function testExtendedIntroduction()
    {
        $test = new ExtendedIntroductionTestClass();
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Tests\Data\Annotations\ExtendedIntroductionTestClass', $test);
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Tests\Data\Annotations\TestInterface1', $test);
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Tests\Data\Annotations\TestInterface2', $test);
    }

    /**
     * Will test whether or not a class containing additional class keywords can be parsed and used
     *
     * @return void
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPostconditionException
     */
    public function testMultiClassTokensInStructure()
    {
        $testClass = new MultiClassTokenTestClass();
        $this->assertInstanceOf('\AppserverIo\Doppelgaenger\Tests\Data\ParserTest\MultiClassTokenTestClass', $testClass);
        $testClass->iContainAClassKeyoword();
    }

    /**
     * Will test whether the returned error line of a precondition is correct in relation to the original class declaration
     *
     * @return void
     */
    public function testErrorLineOfPrecondition()
    {
        $testClass = new ErrorLineTestClass();
        $errorLine = 0;
        try {
            $testClass->iShouldFailAt63(array());

        } catch (\Exception $e) {
            $errorLine = $e->getLine();
        }

        $this->assertEquals(63, $errorLine);
    }

    /**
     * Will test whether the returned error line of a postcondition is correct in relation to the original class declaration
     *
     * @return void
     */
    public function testErrorLineOfPostcondition()
    {
        $testClass = new ErrorLineTestClass();
        $errorLine = 0;
        try {
            $testClass->iShouldFailAt54();

        } catch (\Exception $e) {
            $errorLine = $e->getLine();
        }

        $this->assertEquals(54, $errorLine);
    }

    /**
     * Will test whether the returned error line of a postcondition is correct in relation to the original class declaration even if
     * the declaration contains trailing whitespaces
     *
     * @return void
     */
    public function testErrorLineOfPostconditionWithTrailingWhitespace()
    {
        $testClass = new ErrorLineTestClass();
        $errorLine = 0;
        try {
            $testClass->iShouldFailAt88();

        } catch (\Exception $e) {
            $errorLine = $e->getLine();
        }

        $this->assertEquals(88, $errorLine);
    }

    /**
     * Will test whether the returned error line of an invariant check is correct in relation to the original class declaration and
     * place of the check
     *
     * @return void
     */
    public function testErrorLineOfMethodExitInvariant()
    {
        $testClass = new ErrorLineTestClass();
        $errorLine = 0;
        try {
            $testClass->iShouldFailAt101();

        } catch (\Exception $e) {
            $errorLine = $e->getLine();
        }

        $this->assertEquals(101, $errorLine);
    }

    /**
     * Will test whether the
     *
     * @return void
     *
     * @expectedException \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPostconditionException
     */
    public function testAssignmentOfCorrectFunctionDocBlock()
    {
        $testClass = new BasicTestClass();
        $testClass->iWillFailPostcondition();
    }
}
