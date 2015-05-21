<?php

/**
 * \AppserverIo\Doppelgaenger\Exceptions\BrokenInvariantException
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
 * @link      https://github.com/appserver-io-psr/mop
 * @link      http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\Exceptions;

use AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenInvariantException as MopInvariantnException;
use AppserverIo\Doppelgaenger\Interfaces\ProxyExceptionInterface;

/**
 * This exception might be thrown if a certain invariant gets broken during runtime.
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-psr/mop
 * @link      http://www.appserver.io/
 */
class BrokenInvariantException extends MopInvariantnException implements ProxyExceptionInterface
{
    /**
     * Allows to alter file and line the exception seems to have been thrown/created in
     *
     * @var \AppserverIo\Doppelgaenger\Exceptions\ProxyExceptionTrait
     */
    use ProxyExceptionTrait;
}
