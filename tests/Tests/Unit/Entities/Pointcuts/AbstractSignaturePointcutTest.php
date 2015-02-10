<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Unit\Entities\Pointcuts\AbstractSignaturePointcutTest
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

namespace AppserverIo\Doppelgaenger\Tests\Unit\Entities\Pointcuts;

use AppserverIo\Doppelgaenger\Tests\Mocks\Entities\Pointcuts\MockAbstractSignaturePointcut;

/**
 * Unit testing AbstractSignaturePointcutTest
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class AbstractSignaturePointcutTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Our pointcut instance to test
     *
     * @var \AppserverIo\Doppelgaenger\Tests\Mocks\Entities\Pointcuts\MockAbstractSignaturePointcut $pointcut
     */
    protected $pointcut;

    /**
     * Test setup
     *
     * @return null
     */
    public function setUp()
    {
        $this->pointcut = new MockAbstractSignaturePointcut('', false);
    }

    /**
     * Tests if expressions get straighten correctly
     *
     * @return null
     *
     * @dataProvider testStraightenExpressionProvider
     */
    public function testStraightenExpression($callType, $function, $structure)
    {
        $this->pointcut->callType = $callType;
        $this->pointcut->function = $function;
        $this->pointcut->structure = $structure;
        $this->pointcut->expression = $structure . $callType . $function;

        $definition = $this->getMock('\AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition');
        $definition->expects($this->once())
            ->method('getStructureName')
            ->will($this->returnValue('\AppserverIo\Doppelgaenger\Entities\Definitions\Structure'));
        $definition->expects($this->exactly(2))
            ->method('getName')
            ->will($this->returnValue('testFunction'));

        $this->pointcut->straightenExpression($definition);
        $this->assertEquals('\AppserverIo\Doppelgaenger\Entities\Definitions\Structure' . $callType . 'testFunction', $this->pointcut->getExpression());
        $this->assertEquals('\AppserverIo\Doppelgaenger\Entities\Definitions\Structure', $this->pointcut->structure);
        $this->assertEquals('testFunction', $this->pointcut->function);
    }

    /**
     * Data provider for testStraightenExpression
     *
     * @return array
     */
    public static function testStraightenExpressionProvider()
    {
        return array(
            array('->', '\AppserverIo\Doppelgaenger\Entities\Definitions\*', '*'),
            array('->', '\AppserverIo\Doppelgaenger\Entities\{Definitions,Tests}\Structure', '*'),
            array('->', '\AppserverIo\Doppelgaenger\Entities\{Definitions,Tests}\Structure', '[a-Z]'),
            array('::', '\AppserverIo\Doppelgaenger\Entities\{Definitions,Tests}\Structure', '[a-Z]')
        );
    }
}
