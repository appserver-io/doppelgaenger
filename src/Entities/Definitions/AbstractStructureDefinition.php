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
 * @property array                  $usedStructures      All classes which are referenced by the "use" keyword
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
     * @var array $usedStructures
     */
    protected $usedStructures;

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
     * List of directly defined invariant conditions
     *
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\AssertionList $invariantConditions
     */
    protected $invariantConditions;

    /**
     * List of lists of any ancestral invariants
     *
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\TypedListList $ancestralInvariants
     */
    protected $ancestralInvariants;

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
     * Getter method for attribute $usedStructures
     *
     * @return array
     */
    public function getUsedStructures()
    {
        return $this->usedStructures;
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

    /**
     * Setter method for attribute $docBlock
     *
     * @param string $docBlock Doc block of the structure
     *
     * @return null
     */
    public function setDocBlock($docBlock)
    {
        $this->docBlock = $docBlock;
    }

    /**
     * Setter method for attribute $functionDefinitions
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Lists\FunctionDefinitionList $functionDefinitions List of functions
     *
     * @return null
     */
    public function setFunctionDefinitions(FunctionDefinitionList $functionDefinitions)
    {
        $this->functionDefinitions = $functionDefinitions;
    }

    /**
     * Setter method for attribute $name
     *
     * @param string $name Name of the structure
     *
     * @return null
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Getter method for attribute $namespace
     *
     * @param string $namespace The namespace of the structure
     *
     * @return null
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * Setter method for attribute $path
     *
     * @param string $path Path the definition's file
     *
     * @return null
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Getter method for attribute $usedStructures
     *
     * @param array $usedStructures Array of structures referenced using the "use" statement
     *
     * @return null
     */
    public function setUsedStructures($usedStructures)
    {
        $this->usedStructures = $usedStructures;
    }
}
