<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Definitions\TraitDefinition
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

namespace AppserverIo\Doppelgaenger\Entities\Definitions;

use AppserverIo\Doppelgaenger\Entities\Lists\AssertionList;
use AppserverIo\Doppelgaenger\Entities\Lists\AttributeDefinitionList;
use AppserverIo\Doppelgaenger\Interfaces\PropertiedStructureInterface;

/**
 * This class acts as a DTO-like (we are not immutable due to protected visibility)
 * entity for describing class definitions
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class TraitDefinition extends AbstractStructureDefinition implements PropertiedStructureInterface
{

    /**
     * @const string TYPE The structure type
     */
    const TYPE = 'trait';

    /**
     * List of defined attributes
     *
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\AttributeDefinitionList $attributeDefinitions
     */
    protected $attributeDefinitions;

    /**
     * Trait constants
     *
     * @var array $constants
     */
    protected $constants;

    /**
     * List of directly defined invariant conditions
     *
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\AssertionList $invariantConditions
     */
    protected $invariantConditions;

    /**
     * Default constructor
     *
     * @param string $path                 File path to the class definition
     * @param string $namespace            The namespace the class belongs to
     * @param string $docBlock             The initial class docblock header
     * @param string $name                 Name of the class
     * @param null   $attributeDefinitions List of defined attributes
     * @param null   $invariantConditions  List of directly defined invariant conditions
     */
    public function __construct(
        $path = '',
        $namespace = '',
        $docBlock = '',
        $name = '',
        $attributeDefinitions = null,
        $invariantConditions = null
    ) {
        $this->path = $path;
        $this->namespace = $namespace;
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
     * Will return a list of all dependencies eg. parent class, interfaces and traits.
     *
     * @return array
     */
    public function getDependencies()
    {
        return array();
    }

    /**
     * Will return all invariants.
     *
     * @param boolean $nonPrivateOnly Make this true if you only want conditions which do not have a private context
     *
     * @return \AppserverIo\Doppelgaenger\Entities\Lists\AssertionList
     */
    public function getInvariants($nonPrivateOnly = false)
    {
        // We have to clone it here, otherwise we might have weird side effects, of having the "add()" operation
        // persistent on $this->ancestralInvariants
        $invariants = clone $this->invariantConditions;

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
     * Getter method for attribute $invariantConditions
     *
     * @return null|AssertionList
     */
    public function getInvariantConditions()
    {
        return $this->invariantConditions;
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

    /**
     * Setter method for attribute $attributeDefinitions
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Lists\AttributeDefinitionList $attributeDefinitions List of attribute definitions
     *
     * @return null
     */
    public function setAttributeDefinitions(AttributeDefinitionList $attributeDefinitions)
    {
        $this->attributeDefinitions = $attributeDefinitions;
    }

    /**
     * Setter method for the $constants property
     *
     * @param array $constants Constants the class defines
     *
     * @return null
     */
    public function setConstants($constants)
    {
        $this->constants = $constants;
    }

    /**
     * Setter method for attribute $invariantConditions
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Lists\AssertionList $invariantConditions List of invariant assertions
     *
     * @return null
     */
    public function setInvariantConditions(AssertionList $invariantConditions)
    {
        $this->invariantConditions = $invariantConditions;
    }
}
