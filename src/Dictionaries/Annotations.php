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
 * @category   Appserver
 * @package    TechDivision_PBC
 * @subpackage Dictionaries
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Dictionaries;

/**
 * AppserverIo\Doppelgaenger\Dictionaries\Annotations
 *
 * Contains keywords used as annotations
 *
 * @category   Appserver
 * @package    TechDivision_PBC
 * @subpackage Dictionaries
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class Annotations
{
    /**
     * The keyword for annotations defining invariants
     *
     * @var string INVARIANT
     */
    const INVARIANT = '@invariant';

    /**
     * The keyword for annotations defining postconditions
     *
     * @var string POSTCONDITION
     */
    const POSTCONDITION = '@ensures';

    /**
     * The keyword for annotations defining preconditions
     *
     * @var string PRECONDITION
     */
    const PRECONDITION = '@requires';
}
