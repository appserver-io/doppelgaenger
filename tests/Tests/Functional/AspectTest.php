<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Functional\AspectTest
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

use AppserverIo\Doppelgaenger\Dictionaries\ReservedKeywords;
use AppserverIo\Doppelgaenger\Tests\Data\Advised\AdvisedRegexClass;
use AppserverIo\Doppelgaenger\Tests\Data\Advised\AdvisedTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\Advised\ChildOfAbstractTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\Aspects\MainAspectTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\Advised\PointcutWildcardTestClass1;
use AppserverIo\Doppelgaenger\Tests\Data\Advised\PointcutWildcardTestClass2;

/**
 * Some functional tests for the aspect based advise workflow
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class AspectTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Default constructor
     */
    public function __construct()
    {
        // pipe the aspect through the generator to make it known
        $aspect = new MainAspectTestClass();
    }

    /**
     * Test if a single and directly annotated pointcut works (around advice used)
     *
     * @return void
     */
    public function testSingleDirectPointcut()
    {
        $testClass = new AdvisedTestClass();

        // if the return value could be intercepted
        $this->assertTrue($testClass->publicSimpleMethod());
    }

    /**
     * Test if a pointcut containing a regular expression in the class name will find its target
     *
     * @return void
     */
    public function testRegexAdvisedClass()
    {
        $testClass = new AdvisedRegexClass();

        // just run through, we should not get an exception
        $this->assertTrue($testClass->regexClassMethod());
    }

    /**
     * Test if a pointcut containing a regular expression in the method name will find its target
     *
     * @return void
     */
    public function testRegexAdvisedMethod()
    {
        $testClass = new AdvisedRegexClass();

        // just run through, we should not get an exception
        $this->assertFalse($testClass->regexMethodMethod());
    }

    /**
     * Tests if around advice chaining works at all
     *
     * @return void
     */
    public function testAroundAdviceChainingWorks()
    {
        $testClass = new AdvisedTestClass();

        // just run through, we should not get an exception
        $tmp = $testClass->aroundChainMethod();
        $this->assertTrue(is_array($tmp));
        $this->assertEquals(2, count($tmp));
    }

    /**
     * Tests if around advice chaining works in the correct order of advices
     *
     * @return void
     */
    public function testAroundAdviceChainingOrder()
    {
        $testClass = new AdvisedTestClass();

        // just run through, we should not get an exception
        $tmp = $testClass->aroundChainMethod();
        $this->assertEquals('chainingAdvice1' . ReservedKeywords::ORIGINAL_FUNCTION_SUFFIX, array_pop($tmp));
        $this->assertEquals('chainingAdvice2' . ReservedKeywords::ORIGINAL_FUNCTION_SUFFIX, array_pop($tmp));
    }

    /**
     * Tests if a method of an abstract class can be targeted by direct advice referencing
     *
     * @return void
     */
    public function testDirectAbstractMethodWeaving()
    {
        $testClass = new ChildOfAbstractTestClass();
        $this->assertTrue($testClass->iHaveADirectAdvice());
    }

    /**
     * Tests if a method of an abstract class can be targeted by pointcuts
     *
     * @return void
     */
    public function testPointcutBasedAbstractMethodWeaving()
    {
        $testClass = new ChildOfAbstractTestClass();
        $this->assertTrue($testClass->iHaveAPointcutBasedAdvice());
    }
}
