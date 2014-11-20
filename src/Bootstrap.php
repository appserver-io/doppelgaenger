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
 * This file will bootstrap our library to be used within it's vendor dir
 *
 * @category  Library
 * @package   Doppelgaenger
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2014 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.appserver.io/
 */
$vendorDir = '';
if (realpath(__DIR__ . "/../../../../vendor")) {

    $vendorDir = realpath(__DIR__ . "/../../../../vendor");

} else {

    throw new Exception('Could not locate vendor dir');
}

// Include the composer autoloader as a fallback
$loader = require $vendorDir . DIRECTORY_SEPARATOR . 'autoload.php';
$loader->add('AppserverIo\\Doppelgaenger\\', $vendorDir . DIRECTORY_SEPARATOR . 'appserver-io/doppelgaenger/src');

// We have to register our autoLoader to put our proxies in place
$autoLoader = new AppserverIo\Doppelgaenger\AutoLoader();
$autoLoader->register();
