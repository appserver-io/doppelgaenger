<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Definitions\AttributeDefinition
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

use AppserverIo\Doppelgaenger\Interfaces\DefinitionInterface;

/**
 * Provides a definition of class and trait attributes
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class AttributeDefinition implements DefinitionInterface
{

    /**
     * Default value (if any)
     *
     * @var mixed $defaultValue
     */
    protected $defaultValue;

    /**
     * Is this attribute part of the invariant?
     *
     * @var boolean $inInvariant
     */
    protected $inInvariant;

    /**
     * Is this attribute static?
     *
     * @var boolean $isStatic
     */
    protected $isStatic;

    /**
     * Name of the class attribute
     *
     * @var string $name
     */
    protected $name;

    /**
     * Line of the class attribute's definition
     *
     * @var string $line
     */
    protected $line;

    /**
     * Name of the structure containing this attribute
     *
     * @var string $structureName
     */
    protected $structureName;

    /**
     * Visibility of the attribute
     *
     * @var string $visibility
     */
    protected $visibility;

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->visibility = 'public';
        $this->isStatic = false;
        $this->name = '';
        $this->line = 0;
        $this->defaultValue = null;
        $this->inInvariant = false;
        $this->structureName = '';
    }

    /**
     * Getter method for the $defaultValue property
     *
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Getter method for the $name property
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Getter method for the $line property
     *
     * @return string
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Will return a string representation of this assertion
     *
     * @return string
     */
    public function getString()
    {
        $stringParts = array();

        // Set the visibility
        $stringParts[] = $this->visibility;

        // If we are static, we have to tell so
        if ($this->isStatic === true) {
            $stringParts[] = 'static';
        }

        // Add the name
        $stringParts[] = $this->name;

        // Add any default value we might get
        if ($this->defaultValue !== null) {
            $stringParts[] = '= ' . $this->defaultValue;
        }

        // And don't forget the trailing semicolon
        return implode(' ', $stringParts) . ';';
    }

    /**
     * Getter method for the $structureName property
     *
     * @return string
     */
    public function getStructureName()
    {
        return $this->structureName;
    }

    /**
     * Getter method for the $visibility property
     *
     * @return string
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * If the attribute is mentioned in an invariant
     *
     * @return boolean
     */
    public function inInvariant()
    {
        return $this->inInvariant;
    }

    /**
     * If the attribute is declared static
     *
     * @return boolean
     */
    public function isStatic()
    {
        return $this->isStatic;
    }

    /**
     * Setter method for the $defaultValue property
     *
     * @param mixed $defaultValue Default value of the attribute
     *
     * @return null
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * Setter method for the $inInvariant property
     *
     * @param boolean $inInvariant If the attribute is mentioned in an invariant clause
     *
     * @return null
     */
    public function setInInvariant($inInvariant)
    {
        $this->inInvariant = $inInvariant;
    }

    /**
     * Setter method for the $isStatic property
     *
     * @param boolean $isStatic If the attribute is declared static
     *
     * @return null
     */
    public function setIsStatic($isStatic)
    {
        $this->isStatic = $isStatic;
    }

    /**
     * Setter method for the $name property
     *
     * @param string $name Name of the attribute
     *
     * @return null
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Setter method for the $line property
     *
     * @param string $line Line of the attribute's definition
     *
     * @return null
     */
    public function setLine($line)
    {
        $this->line = $line;
    }

    /**
     * Setter method for the $structureName property
     *
     * @param string $structureName Name of the containing structure
     *
     * @return null
     */
    public function setStructureName($structureName)
    {
        $this->structureName = $structureName;
    }

    /**
     * Setter method for the $visibility property
     *
     * @param string $visibility Visibility of the attribute
     *
     * @return null
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
    }
}
