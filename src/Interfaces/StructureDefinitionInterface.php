<?php

/**
 * \AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface
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

use AppserverIo\Doppelgaenger\Entities\Lists\TypedListList;

/**
 * Public interface for structure definitions
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
interface StructureDefinitionInterface
{

    /**
     * Will return a list of all dependencies of a structure like parent class, implemented interfaces, etc.
     *
     * @return array
     */
    public function getDependencies();

    /**
     * Getter method for all function definitions a structure might have
     *
     * @return null|\AppserverIo\Doppelgaenger\Entities\Lists\FunctionDefinitionList
     */
    public function getFunctionDefinitions();

    /**
     * Will return all invariants of a structure.
     *
     * @return TypedListList
     */
    public function getInvariants();

    /**
     * Will return the qualified name of a structure
     *
     * @return string
     */
    public function getQualifiedName();

    /**
     * Will return the type of the definition.
     *
     * @return string
     */
    public function getType();

    /**
     * Will return true if the structure has (a) parent structure(s).
     * Will return false if not.
     *
     * @return bool
     */
    public function hasParents();
}
