<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Unit\FunctionParserTest
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
namespace AppserverIo\Doppelgaenger\Tests\Unit;

use AppserverIo\Doppelgaenger\Parser\FunctionParser;
use JMS\Serializer\Tests\Fixtures\Publisher;
use AppserverIo\Doppelgaenger\Config;

/**
 * Test class for the FunctionParser class
 *
 * @author Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link https://github.com/appserver-io/doppelgaenger
 * @link http://www.appserver.io/
 */
class FunctionParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The function parser instance we will run our tests against
     *
     * @var \AppserverIo\Doppelgaenger\Parser\FunctionParser $testClass
     */
    protected $testClass;

    /**
     * Set the tests up with a new function parser instance for each test
     *
     * @return null
     */
    public function setUp()
    {
        $this->testClass = new FunctionParser('', new Config(), null, null, null, array(
            'no tokens so far'
        ));
    }

    /**
     * Will provide method tokens
     *
     * @return array
     */
    public function getParameterDefinitionListProvider()
    {
        return array(
            array(
                $this->tokensFixture[0],
                2
            )
        );
    }

    /**
     * Will provide method tokens
     *
     * @return array
     */
    public function getParameterDefinitionListComplexDefaultValuesProvider()
    {
        return array(
            array(
                $this->tokensFixture[0],
                array('false', '["oneTimeSupplier" => false]')
            )
        );
    }

    /**
     * Will test if we can parse different parameter definitions
     *
     * @param array   $tokens        The tokens to parse from
     * @param integer $expectedCount The expected parameter list count
     *
     * @return void @dataProvider getParameterDefinitionListProvider
     */
    public function testGetParameterDefinitionList(array $tokens, $expectedCount)
    {
        $list = $this->testClass->getParameterDefinitionList($tokens);
        $this->assertCount($expectedCount, $list);
    }

    /**
     * Will test if we can parse parameter definitions with complex default values
     *
     * @param array $tokens        The tokens to parse from
     * @param array $defaultValues The expected default values of the parameters
     *
     * @return void @dataProvider getParameterDefinitionListComplexDefaultValuesProvider
     */
    public function testGetParameterDefinitionListComplexDefaultValues(array $tokens, array $defaultValues)
    {
        $list = $this->testClass->getParameterDefinitionList($this->tokensFixture[0]);

        foreach ($list as $key => $listEntry) {
            $this->assertEquals($defaultValues[$key], trim($listEntry->defaultValue));
        }
    }

    /**
     * Array of token fixtures we use for our tests
     *
     * @var array $tokensFixture
     */
    protected $tokensFixture = array(
        array(
            0 => array(
                0 => T_PUBLIC,
                1 => 'public',
                2 => 1
            ),
            1 => array(
                0 => 377,
                1 => ' ',
                2 => 1
            ),
            2 => array(
                0 => T_FUNCTION,
                1 => 'function',
                2 => 1
            ),
            3=> array(
                0 => 377,
                1 => ' ',
                2 => 1
            ),
            4 => array(
                0 => 308,
                1 => 'fetchAll',
                2 => 1
            ),
            5 => '(',
            6 => array(
                0 => 310,
                1 => '$noConditions',
                2 => 1
            ),
            7 => array(
                0 => 377,
                1 => ' ',
                2 => 1
            ),
            8 => '=',
            9 => array(
                0 => 377,
                1 => ' ',
                2 => 1
            ),
            10 => array(
                0 => 308,
                1 => 'false',
                2 => 1
            ),
            11 => ',',
            12 => array(
                0 => 377,
                1 => ' ',
                2 => 1
            ),
            13 => array(
                0 => 364,
                1 => 'array',
                2 => 1
            ),
            14 => array(
                0 => 377,
                1 => ' ',
                2 => 1
            ),
            15 => array(
                0 => 310,
                1 => '$conditions',
                2 => 1
            ),
            16 => array(
                0 => 377,
                1 => ' ',
                2 => 1
            ),
            17 => '=',
            18 => array(
                0 => 377,
                1 => ' ',
                2 => 1
            ),
            19 => '[',
            20 => array(
                0 => 316,
                1 => '"oneTimeSupplier"',
                2 => 1
            ),
            21 => array(
                0 => 377,
                1 => ' ',
                2 => 1
            ),
            22 => array(
                0 => 362,
                1 => '=>',
                2 => 1
            ),
            23 => array(
                0 => 377,
                1 => ' ',
                2 => 1
            ),
            24 => array(
                0 => 308,
                1 => 'false',
                2 => 1
            ),
            25 => ']',
            26 => ')',
            27 => '{',
            28 => array(
                0 => 377,
                1 => '
',
                2 => 1
            ),
            29 => '}'
        )
    );
}
