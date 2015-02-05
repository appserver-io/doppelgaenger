<?php

/**
 * File bootstrapping the PHPUnit test environment
 *
 * PHP version 5
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
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

// we should clear the cache directory from the result of former runs
$cacheDir = $config->getValue('cache/dir');
foreach (scandir($cacheDir) as $cachedFile) {
    // clean the files but do not delete the .gitignore file
    if ($cachedFile === '.gitignore' || $cachedFile === '.' || $cachedFile === '..') {
        continue;
    }

    unlink($cacheDir . DIRECTORY_SEPARATOR . $cachedFile);
}
