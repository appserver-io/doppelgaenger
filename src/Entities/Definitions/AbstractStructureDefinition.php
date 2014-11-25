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

use AppserverIo\Doppelgaenger\Entities\Lists\FunctionDefinitionList;
use AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface;

/**
 * AppserverIo\Doppelgaenger\Entities\Definitions\AbstractStructureDefinition
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
 *
 * @property string                 $path                File path to the class definition
 * @property string                 $namespace           The namespace the class belongs to
 * @property array                  $usedNamespaces      All classes which are referenced by the "use" keyword
 * @property string                 $docBlock            The initial class docblock header
 * @property string                 $name                Name of the class
 * @property FunctionDefinitionList $functionDefinitions List of methods
 */
abstract class AbstractStructureDefinition extends AbstractDefinition implements StructureDefinitionInterface
{
    /**
     * File path to the class definition
     *
     * @var string $path
     */
    protected $path;

    /**
     * The namespace the class belongs to
     *
     * @var string $namespace
     */
    protected $namespace;

    /**
     * All classes which are referenced by the "use" keyword
     *
     * @var array $usedNamespaces
     */
    protected $usedNamespaces;

    /**
     * The initial class docblock header
     *
     * @var string $docBlock
     */
    protected $docBlock;

    /**
     * Name of the class
     *
     * @var string $name
     */
    protected $name;

    /**
     * List of methods this class defines
     *
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\FunctionDefinitionList $functionDefinitions
     */
    protected $functionDefinitions;

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
     * Getter method for attribute $docBlock
     *
     * @return string
     */
    public function getDocBlock()
    {
        return $this->docBlock;
    }

    /**
     * Getter method for attribute $functionDefinitions
     *
     * @return null|\AppserverIo\Doppelgaenger\Entities\Lists\FunctionDefinitionList
     */
    public function getFunctionDefinitions()
    {
        return $this->functionDefinitions;
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
     * Getter method for attribute $name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Getter method for attribute $namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Getter method for attribute $path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Getter method for attribute $usedNamespace
     *
     * @return array
     */
    public function getUsedNamespaces()
    {
        return $this->usedNamespaces;
    }

    /**
     * Will return the qualified name of a structure
     *
     * @return string
     */
    public function getQualifiedName()
    {
        if (empty($this->namespace)) {

            return $this->name;

        } else {

            return ltrim($this->namespace, '\\') . '\\' . $this->name;
        }
    }

    /**
     * Will return the type of the definition.
     *
     * @return string
     */
    public function getType()
    {
        return static::TYPE;
    }

    /**
     * Does this structure have parent structures.
     * We are talking parents here, not implemented interfaces or used traits
     *
     * @return boolean
     */
    public function hasParents()
    {
        return !empty($this->extends);
    }
}
