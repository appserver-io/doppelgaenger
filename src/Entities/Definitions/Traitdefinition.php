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

namespace AppserverIo\Doppelgaenger\Entities\Definitions;

use AppserverIo\Doppelgaenger\Entities\Lists\AssertionList;
use AppserverIo\Doppelgaenger\Entities\Lists\AttributeDefinitionList;

/**
 * AppserverIo\Doppelgaenger\Entities\Definitions\Traitdefinition
 *
 * This class acts as a DTO-like (we are not immutable due to protected visibility)
 * entity for describing class definitions
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class TraitDefinition extends AbstractStructureDefinition
{

    /**
     * @const string TYPE The structure type
     */
    const TYPE = 'trait';

    /**
     * @var AttributeDefinitionList $attributeDefinitions List of defined attributes
     */
    protected $attributeDefinitions;

    /**
     * @var AssertionList $invariantConditions List of directly defined invariant conditions
     */
    protected $invariantConditions;

    /**
     * Default constructor
     *
     * @param string $path                 File path to the class definition
     * @param string $namespace            The namespace the class belongs to
     * @param array  $usedNamespaces       All classes which are referenced by the "use" keyword
     * @param string $docBlock             The initial class docblock header
     * @param string $name                 Name of the class
     * @param null   $attributeDefinitions List of defined attributes
     * @param null   $invariantConditions  List of directly defined invariant conditions
     */
    public function __construct(
        $path = '',
        $namespace = '',
        $usedNamespaces = array(),
        $docBlock = '',
        $name = '',
        $attributeDefinitions = null,
        $invariantConditions = null
    ) {
        $this->path = $path;
        $this->namespace = $namespace;
        $this->usedNamespaces = $usedNamespaces;
        $this->docBlock = $docBlock;
        $this->name = $name;
        $this->attributeDefinitions = is_null(
            $attributeDefinitions
        ) ? new AttributeDefinitionList() : $attributeDefinitions;
        $this->invariantConditions = is_null($invariantConditions) ? new AssertionList() : $invariantConditions;
    }

    /**
     * Getter method for attribute $attributeDefinitions
     *
     * @return null|AttributeDefinitionList
     */
    public function getAttributeDefinitions()
    {
        return $this->attributeDefinitions;
    }

    /**
     * Getter method for attribute $constants
     *
     * @return array
     */
    public function getConstants()
    {
        return $this->constants;
    }

    /**
     * Getter method for attribute $invariantConditions
     *
     * @return null|AssertionList
     */
    public function getInvariantConditions()
    {
        return $this->invariantConditions;
    }

    /**
     * Will return all invariants. direct and introduced (by ancestral structures) alike.
     *
     * @param boolean $nonPrivateOnly Make this true if you only want conditions which do not have a private context
     *
     * @return \AppserverIo\Doppelgaenger\Entities\Lists\TypedListList
     */
    public function getInvariants($nonPrivateOnly = false)
    {
        // We have to clone it here, otherwise we might have weird side effects, of having the "add()" operation
        // persistent on $this->ancestralInvariants
        $invariants = clone $this->ancestralInvariants;
        $invariants->add($this->invariantConditions);

        // If we need to we will filter all the non private conditions from the lists
        if ($nonPrivateOnly === true) {

            $invariantListIterator = $invariants->getIterator();
            foreach ($invariantListIterator as $invariantList) {

                $invariantIterator = $invariantList->getIterator();
                foreach ($invariantIterator as $key => $invariant) {

                    if ($invariant->isPrivateContext()) {

                        $invariantList->delete($key);
                    }
                }
            }
        }

        // Return what is left
        return $invariants;
    }

    /**
     * Will return a list of all dependencies eg. parent class, interfaces and traits.
     *
     * @return array
     */
    public function getDependencies()
    {
        // Get our interfaces
        $result = $this->implements;

        // We got an error that this is nor array, weird but build up a final frontier here
        if (!is_array($result)) {

            $result = array($result);
        }

        // Add our parent class (if any)
        if (!empty($this->extends)) {

            $result[] = $this->extends;
        }

        return $result;
    }

    /**
     * Does this structure have parent structures?
     * Traits do not by default
     *
     * @return boolean
     */
    public function hasParents()
    {
        return false;
    }
}
