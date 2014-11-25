<?php

/**
 * File bootstrapping the PHPUnit test environment
 *
 * PHP version 5
 *
 * @category  Doppelgaenger
 * @package   AppserverIo\Doppelgaenger
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @copyright 2014 TechDivision GmbH - <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.techdivision.com/
 */

use \AppserverIo\Doppelgaenger\Config;

// Get the vendor dir
$vendorDir = __DIR__ . "/vendor";

// Include the composer autoloader as a fallback
$loader = require $vendorDir . DIRECTORY_SEPARATOR . 'autoload.php';
$loader->add('AppserverIo\\Doppelgaenger', array(__DIR__ . '/src', __DIR__ . '/tests'));

// Load the test config file
$config = new Config();
$config->load(
    __DIR__ . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'Tests' . DIRECTORY_SEPARATOR . 'Data' .
    DIRECTORY_SEPARATOR . 'tests.conf.json'
);

// We have to register our autoLoader to put our proxies in place
$autoLoader = new AppserverIo\Doppelgaenger\AutoLoader($config);
$autoLoader->register();
