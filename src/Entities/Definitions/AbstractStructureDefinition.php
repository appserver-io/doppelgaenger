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
 * @link       http://www.appserver.io//
 */
abstract class AbstractStructureDefinition extends AbstractDefinition implements StructureDefinitionInterface
{
    /**
     * @var string $path File path to the class definition
     */
    protected $path;

    /**
     * @var string $namespace The namespace the class belongs to
     */
    protected $namespace;

    /**
     * @var array $usedNamespaces All classes which are referenced by the "use" keyword
     */
    protected $usedNamespaces;

    /**
     * @var string $docBlock The initial class docblock header
     */
    protected $docBlock;

    /**
     * @var string $name Name of the class
     */
    protected $name;

    /**
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\FunctionDefinitionList $functionDefinitions List of methods this class
     *          defines
     */
    protected $functionDefinitions;

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
