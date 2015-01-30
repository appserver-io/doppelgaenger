<?php

/**
 * \AppserverIo\Doppelgaenger\Interfaces\PropertiedStructureInterface
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

namespace AppserverIo\Doppelgaenger\Interfaces;

/**
 * Interface which will be implemented by structures which are able to have properties.
 * These are at least classes and traits
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
interface PropertiedStructureInterface
{
    /**
     * Getter method for attribute $attributeDefinitions
     *
     * @return null|\AppserverIo\Doppelgaenger\Entities\Lists\AttributeDefinitionList
     */
    public function getAttributeDefinitions();

    /**
     * Getter method for attribute $invariantConditions
     *
     * @return null|\AppserverIo\Doppelgaenger\Entities\Lists\AssertionList
     */
    public function getInvariantConditions();
}
