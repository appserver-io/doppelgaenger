<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\BasicTestClass
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

namespace AppserverIo\Doppelgaenger\Tests\Data;

use AppserverIo\Doppelgaenger\Entities\Assertion;
use AppserverIo\Doppelgaenger\Entities\AssertionList;
use AppserverIo\Doppelgaenger\Entities\ClassDefinition;
use AppserverIo\Doppelgaenger\Entities\FunctionDefinition;
use AppserverIo\Doppelgaenger\Entities\FunctionDefinitionList;
use AppserverIo\Doppelgaenger\Entities\ScriptDefinition;

/**
 * Class which contains a basic assortment of tests
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 *
 * @invariant $this->motd === 'Welcome stranger!'
 */
class BasicTestClass
{
    /**
     * @var string
     */
    private $motd = 'Welcome stranger!';

    /**
     * @requires $param1 < 27 && $param1 > 18 or $param1 === 17
     *
     * @param integer   $param1
     * @param string    $param2
     * @param \Exception $param3
     *
     * @return string
     */
    public function concatSomeStuff($param1, $param2, \Exception $param3)
    {
        return (string)$param1 . $param2 . $param3->getMessage();
    }

    /**
     * @requires $param1 == 'null'
     *
     * @param string $param1
     *
     * @return array
     */
    public function stringToArray($param1)
    {
        return array($param1);
    }

    /**
     * @requires $ourString === 'stranger'
     *
     * @param string $ourString
     *
     * @ensures $dgResult === 'Welcome stranger'
     *
     * @return string
     */
    public function stringToWelcome($ourString)
    {
        return "Welcome " . $ourString;
    }

    /**
     *
     */
    public function iBreakTheInvariant()
    {
        $this->motd = 'oh no!!!';
    }

    /**
     *
     */
    public function iDontBreakTheInvariant()
    {
        // We will break the invariant here
        $this->invariantBreaker();

        // and do some stuff here
        $iAmUseless = $this->motd;

        // now we repair the invariant again
        $this->invariantRepair();

        // We return something just for the hell of it
        return $iAmUseless;
    }

    /**
     * Will break the invariant
     */
    private function invariantBreaker($test = array())
    {
        $this->motd = 'We are doomed!';
    }

    /**
     * Will repair the invariant
     */
    private function invariantRepair()
    {
        $this->motd = 'Welcome stranger!';
    }
}
