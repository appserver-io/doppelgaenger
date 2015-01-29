<?php

/**
 * \AppserverIo\Doppelgaenger\Interfaces\StreamFilterInterface
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

namespace AppserverIo\Doppelgaenger\Interfaces;

/**
 * An interface defining the functionality of any possible stream filter class
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
interface StreamFilterInterface
{
    /**
     * Will return the order number the concrete filter has been constantly assigned
     *
     * @return int
     */
    public function getFilterOrder();

    /**
     * Will return true if all dependencies for this filter were met.
     * This mostly means that needed filters are appended in front of this one
     *
     * @return boolean
     */
    public function dependenciesMet();
}
