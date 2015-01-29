<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Lists\TypedList
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\Entities\Lists;

/**
 * Typed list usable with a wide variety of types
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
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
        parent::__construct();

        $this->itemType = $itemType;
        $this->defaultOffset = $defaultOffset;
    }
}
