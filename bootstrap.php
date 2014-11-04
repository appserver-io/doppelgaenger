<?php

/**
 * File bootstrapping the PHPUnit test environment
 *
 * PHP version 5
 *
 * @category  Appserver
 * @package   Doppelgaenger
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @copyright 2014 TechDivision GmbH - <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.techdivision.com/
 */

$loader = require 'vendor/autoload.php';
$loader->add('AppserverIo\\Doppelgaenger', array('src', 'tests'));
