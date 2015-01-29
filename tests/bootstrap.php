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
 * File bootstrapping the PHPUnit test environment
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */

use AppserverIo\Doppelgaenger\Config;

// Get the vendor dir
$vendorDir = '';
if (realpath(__DIR__ . "/../../vendor")) {
    $vendorDir = realpath(__DIR__ . "/../../vendor");

} else {
    throw new Exception('Could not locate vendor dir');
}

// Include the composer autoloader as a fallback
$loader = require $vendorDir . DIRECTORY_SEPARATOR . 'autoload.php';
$loader->add('AppserverIo\\Doppelgaenger\\', $vendorDir . DIRECTORY_SEPARATOR . 'appserver-io/doppelgaenger/src');

// Load the test config file
$config = new Config();
$config->load(
    __DIR__ . DIRECTORY_SEPARATOR . 'Tests' . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'tests.conf.json'
);

// We have to register our autoLoader to put our proxies in place
$autoLoader = new AppserverIo\Doppelgaenger\AutoLoader($config);
$autoLoader->register();
