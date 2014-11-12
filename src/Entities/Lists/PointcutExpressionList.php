<?php
/**
 * File containing the PointcutList class
 *
 * PHP version 5
 *
 * @category   Doppelgaenger
 * @package    AppserverIo\Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Entities\Lists;

/**
 * AppserverIo\Doppelgaenger\Entities\Lists\PointcutList
 *
 * A typed list for pointcuts
 *
 * @category   Doppelgaenger
 * @package    AppserverIo\Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class PointcutExpressionList extends AbstractTypedList
{
    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->itemType = 'AppserverIo\Doppelgaenger\Entities\PointcutExpression';
    }
}
