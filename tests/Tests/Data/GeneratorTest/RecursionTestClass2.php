<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\GeneratorTest\RecursionTestClass2
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

namespace AppserverIo\Doppelgaenger\Tests\Data\GeneratorTest;

/**
 * Class used to test the safety against an endless recursion introduced by the usage of parent::-calls
 * in a direct child/parent relationship
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class RecursionTestClass2 extends RecursionTestClass1
{

    /**
     * Counter to measure calls
     *
     * @var integer $recursionCounter
     */
    public $recursionCounter = 0;

    /**
     * Method which should not be called recursively more than once
     *
     * @return void
     */
    public function iDontWantToBeRecursive()
    {
        // increment the counter
        $this->recursionCounter ++;

        // stop execution at a point which MUST have been an unwanted recursion
        if ($this->recursionCounter < 10) {
            parent::iDontWantToBeRecursive();
        }
    }
}
