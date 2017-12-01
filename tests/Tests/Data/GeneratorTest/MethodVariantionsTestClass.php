<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\Data\GeneratorTest\MethodVariantionsAbstractTestClass
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
 * Class used to test basic method declaration variations
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class MethodVariantionsTestClass extends MethodVariantionsAbstractTestClass
{

    /**
     * A public method
     *
     * @return void
     */
    public function iAmPublic()
    {
    }

    /**
     * A protected method
     *
     * @return void
     */
    protected function iAmProtected()
    {
    }

    /**
     * A private method
     *
     * @return void
     */
    protected function iAmPrivate()
    {
    }

    /**
     * A final public method
     *
     * @return void
     */
    final public function iAmFinalAndPublic()
    {
    }

    /**
     * A final protected method
     *
     * @return void
     */
    final protected function iAmFinalAndProtected()
    {
    }

    /**
     * A public static method
     *
     * @return void
     */
    public static function iAmPublicAndStatic()
    {
    }

    /**
     * A protected static method
     *
     * @return void
     */
    protected static function iAmProtectedAndStatic()
    {
    }

    /**
     * A private static method
     *
     * @return void
     */
    private static function iAmPrivateAndStatic()
    {
    }

    /**
     * A final public static method
     *
     * @return void
     */
    final public static function iAmFinalAndPublicAndStatic()
    {
    }

    /**
     * A final protected static method
     *
     * @return void
     */
    final protected static function iAmFinalAndProtectedAndStatic()
    {
    }

    /**
     * An abstract public method
     *
     * @return void
     */
    public function iAmAbstractAndPublic()
    {
    }

    /**
     * An abstract protected method
     *
     * @return void
     */
    protected function iAmAbstractAndprotected()
    {
    }
}
