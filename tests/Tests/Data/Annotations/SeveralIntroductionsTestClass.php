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
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Tests\Data\Annotations;

/**
 * AppserverIo\Doppelgaenger\Tests\Data\Annotations\SeveralIntroductionsTestClass
 *
 * Test class which is used to test several introductions
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Tests
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 *
 * @Introduce(interface="\AppserverIo\Doppelgaenger\Tests\Data\Annotations\TestInterface1",
 *      implementation="\AppserverIo\Doppelgaenger\Tests\Data\Annotations\TestTrait1")
 * @Introduce(interface="\AppserverIo\Doppelgaenger\Tests\Data\Annotations\TestInterface2", implementation="\AppserverIo\Doppelgaenger\Tests\Data\Annotations\TestTrait2")
 */
class SeveralIntroductionsTestClass
{

}

 