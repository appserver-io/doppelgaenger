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
 * @subpackage Interfaces
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\Interfaces;

use AppserverIo\Doppelgaenger\Entities\Lists\TypedListList;

/**
 * AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface
 *
 * Public interface for structure definitions
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Interfaces
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
interface StructureDefinitionInterface
{
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
     * Will return a list of all dependencies of a structure like parent class, implemented interfaces, etc.
     *
     * @return array
     */
    public function getDependencies();

    /**
     * Will return true if the structure has (a) parent structure(s).
     * Will return false if not.
     *
     * @return bool
     */
    public function hasParents();

    /**
     * Will return all invariants of a structure.
     *
     * @return TypedListList
     */
    public function getInvariants();
}
