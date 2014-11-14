<?php
/**
 * File containing the AttributeDefinitionList class
 *
 * PHP version 5
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Entities\Lists;

/**
 * AppserverIo\Doppelgaenger\Entities\Lists\AttributeDefinitionList
 *
 * A typed list for AttributeDefinition objects
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class AttributeDefinitionList extends AbstractTypedList
{

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->itemType = 'AppserverIo\Doppelgaenger\Entities\Definitions\AttributeDefinition';
        $this->defaultOffset = 'name';
    }
}
