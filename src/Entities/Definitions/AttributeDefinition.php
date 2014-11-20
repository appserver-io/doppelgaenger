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

use AppserverIo\Doppelgaenger\Entities\AbstractLockableEntity;
use AppserverIo\Doppelgaenger\Interfaces\DefinitionInterface;

/**
 * AppserverIo\Doppelgaenger\Entities\Definitions\AttributeDefinition
 *
 * Provides a definition of class and trait attributes
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class AttributeDefinition extends AbstractLockableEntity implements DefinitionInterface
{
    /**
     * @var string $visibility Visibility of the attribute
     */
    protected $visibility;

    /**
     * @var boolean $isStatic Is this attribute static?
     */
    protected $isStatic;

    /**
     * @var string $name Name of the class attribute
     */
    protected $name;

    /**
     * @var mixed $defaultValue Default value (if any)
     */
    protected $defaultValue;

    /**
     * @var bool $inInvariant Is this attribute part of the invariant?
     */
    protected $inInvariant;

    /**
     * Name of the structure containing this attribute
     *
     * @var string $structureName
     */
    protected $structureName;

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->visibility = 'public';
        $this->isStatic = false;
        $this->name = '';
        $this->defaultValue = null;
        $this->inInvariant = false;
        $this->structureName = '';
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
     * Getter method for attribute $structureName
     *
     * @return string
     */
    public function getStructureName()
    {
        return $this->structureName;
    }
}
