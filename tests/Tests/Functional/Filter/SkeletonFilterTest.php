<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Functional\Filter\SkeletonFilterTest
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
 * @copyright 2019 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\Tests\Functional\Filter;

use AppserverIo\Doppelgaenger\Tests\Data\Filter\SkeletonFilterTestClass;

/**
 * Some functional tests for the class parser
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2019 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class SkeletonFilterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Instance of our test class
     *
     * @var \AppserverIo\Doppelgaenger\Parser\ClassParser $testClass
     */
    protected $testClass;

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->testClass = new SkeletonFilterTestClass();
    }

    /**
     * Tests if functions with additional whitespaces in between the function keyword and the name are generated correctly
     * @see https://github.com/appserver-io/appserver/issues/1121
     *
     * @return void
     */
    public function testMethodWithAdditionalSpacesBeforeTheName()
    {
        $this->testClass->methodWithAdditionalSpacesBeforeTheName();
    }
}
