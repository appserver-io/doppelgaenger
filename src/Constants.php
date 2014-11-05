<?php
/**
 * File containing all constants we use throughout our library
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

// We might not have a PHP > 5.3 on our hands.
// To avoid parser errors we will define used constants here
if (!defined('T_TRAIT')) {

    define('T_TRAIT', 355);
}
