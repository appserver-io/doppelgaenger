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
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\Entities\Lists;

/**
 * AppserverIo\Doppelgaenger\Entities\Lists\TypedList
 *
 * Typed list usable with a wide variety of types
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class TypedList extends AbstractTypedList
{

    /**
     * Default constructor
     *
     * @param string $itemType      Qualified name of the type the expected entries will have
     * @param string $defaultOffset The name of the added entry's property whose value will be used as offset
     */
    public function __construct($itemType, $defaultOffset = '')
    {
        $this->itemType = $itemType;
        $this->defaultOffset = $defaultOffset;
    }
}
