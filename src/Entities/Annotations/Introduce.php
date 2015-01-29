<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Annotations\Introduce
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

namespace AppserverIo\Doppelgaenger\Entities\Annotations;

/**
 * Annotation class which is used to introduce new characteristics into a class.
 * Will result in a trait-supported implementation of a specified interface
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 *
 * @Annotation
 * @Target({"CLASS"})
 */
class Introduce
{
    /**
     * The annotation which identifies this annotation class
     *
     * @var string
     */
    const ANNOTATION = 'Introduce';

    /**
     * This method returns the class name as a string
     *
     * @return string
     */
    public static function __getClass()
    {
        return __CLASS__;
    }
}
