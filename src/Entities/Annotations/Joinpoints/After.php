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
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\Entities\Annotations\Joinpoints;

/**
 * AppserverIo\Doppelgaenger\Entities\Annotations\Joinpoints\After
 *
 * Annotation class which is used to specify "after" advice usage
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 *
 * @Annotation
 * @Target({"METHOD","PROPERTY"})
 */
class After
{
    /**
     * The annotation which identifies this annotation class
     *
     * @var string
     */
    const ANNOTATION = 'After';

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
