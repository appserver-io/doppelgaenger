<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\Advised\PointcutReferencingTestClass
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

namespace AppserverIo\Doppelgaenger\Tests\Data\Advised;

/**
 * Test class used for our PointcutReferencingTest tests
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class PointcutReferencingTestClass
{

    /**
     * Property which can be used to temporarily store arbitrary data so transport the internal flow to our test classes
     *
     * @var mixed $testStorage
     */
    public $testStorage;

    /**
     * Property which can be used to temporarily store arbitrary data so transport the internal flow to our test classes
     *
     * @var mixed $staticStorage
     */
    public static $staticStorage;

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->testStorage = null;
    }

    /**
     * Used to test pointcut based weaving of "Before" advices
     *
     * @return string
     */
    public function iHaveABeforeAdvice()
    {
        return 'iHaveABeforeAdvice';
    }

    /**
     * Used to test pointcut based weaving of "After" advices
     *
     * @return string
     */
    public function iHaveAnAfterAdviceAndReturnSomething()
    {
        return 'iHaveAnAfterAdviceAndReturnSomething';
    }

    /**
     * Used to test pointcut based weaving of "After" advices
     *
     * @return null
     *
     * @throws \Exception
     */
    public function iHaveAnAfterAdviceAndThrowSomething()
    {
        throw new \Exception();
    }

    /**
     * Used to test pointcut based weaving of "AfterReturning" advices
     *
     * @return string
     */
    public function iHaveAnAfterThrowingAdviceAndReturnSomething()
    {
        return 'iHaveAnAfterThrowingAdviceAndReturnSomething';
    }

    /**
     * Used to test pointcut based weaving of "AfterReturning" advices
     *
     * @return string
     *
     * @throws \Exception
     */
    public function iHaveAnAfterThrowingAdviceAndThrowSomething()
    {
        throw new \Exception();
    }

    /**
     * Used to test pointcut based weaving of "AfterReturning" advices
     *
     * @return string
     */
    public function iHaveAnAfterReturningAdviceAndReturnSomething()
    {
        return 'iHaveAnAfterReturningAdviceAndReturnSomething';
    }

    /**
     * Used to test pointcut based weaving of "AfterReturning" advices
     *
     * @return string
     *
     * @throws \Exception
     */
    public function iHaveAnAfterReturningAdviceAndThrowSomething()
    {
        throw new \Exception();
    }

    /**
     * Used to test pointcut based weaving of "Around" advices
     *
     * @return string
     */
    public function iHaveAnAroundAdvice()
    {
        return 'iHaveAnAroundAdvice';
    }
}
