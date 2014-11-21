<?php

/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\Tests\Functional;

use AppserverIo\Doppelgaenger\Tests\Data\AdvisedTestClass;
use AppserverIo\Doppelgaenger\Tests\Data\Aspects\MainAspectTestClass;

/**
 * AppserverIo\Doppelgaenger\Tests\Functional\AspectTest
 *
 * Some functional tests for the aspect based advise workflow
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class AspectTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test if a single and directly annotated pointcut works (around advice used)
     *
     * @return null
     */
    public function testSingleDirectPointcut()
    {
        // TODO make this a manual process
        // pipe the aspect through the generator to make it known
        $aspect = new MainAspectTestClass();

        $testClass = new AdvisedTestClass();

        // if the return value could be intercepted
        $this->assertTrue($testClass->publicSimpleMethod());
    }
}
