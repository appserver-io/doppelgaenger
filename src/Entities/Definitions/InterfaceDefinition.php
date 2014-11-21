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
use AppserverIo\Doppelgaenger\Entities\Lists\FunctionDefinitionList;
use AppserverIo\Doppelgaenger\Entities\Lists\TypedListList;

/**
 * AppserverIo\Doppelgaenger\Entities\Definitions\InterfaceDefinition
 *
 * This class acts as a DTO-like (we are not immutable due to protected visibility)
 * entity for describing interface definitions
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class InterfaceDefinition extends AbstractStructureDefinition
{

    /**
     * @const string TYPE The structure type
     */
    const TYPE = 'interface';

    /**
     * @var array $extends The parent interfaces (if any)
     */
    protected $extends;

    /**
     * @var array $constants Possible constants the interface defines
     */
    protected $constants;

    /**
     * @var AssertionList $invariantConditions Invariant conditions
     * TODO get rid of this as it breaks information hiding
     */
    protected $invariantConditions;

    /**
     * @var TypedListList $ancestralInvariants Ancestral invariants
     * TODO get rid of this as it breaks information hiding
     */
    protected $ancestralInvariants;

    /**
     * Default constructor
     *
     * TODO The constructor does not use all members
     *
     * @param string                      $docBlock            DocBlock header of the interface
     * @param string                      $name                $name Interface name
     * @param string                      $namespace           The namespace the definition resides in
     * @param array                       $extends             The parent interfaces (if any)
     * @param array                       $constants           Possible constants the interface defines
     * @param AssertionList|null          $invariantConditions Invariant conditions
     * @param TypedListList|null          $ancestralInvariants Ancestral invariants
     * @param FunctionDefinitionList|null $functionDefinitions List of functions defined within the interface
     */
    public function __construct(
        $docBlock = '',
        $name = '',
        $namespace = '',
        $extends = array(),
        $constants = array(),
        $invariantConditions = null,
        $ancestralInvariants = null,
        $functionDefinitions = null
    ) {
        $this->docBlock = $docBlock;
        $this->name = $name;
        $this->namespace = $namespace;
        $this->extends = $extends;
        $this->constants = $constants;
        $this->invariantConditions = is_null($invariantConditions) ? new AssertionList() : $invariantConditions;
        $this->ancestralInvariants = is_null($ancestralInvariants) ? new TypedListList() : $ancestralInvariants;
        $this->functionDefinitions = is_null(
            $functionDefinitions
        ) ? new FunctionDefinitionList() : $functionDefinitions;
    }

    /**
     * Will return all invariants for this interface, direct or indirect
     *
     * @return TypedListList
     * TODO get rid of this
     */
    public function getInvariants()
    {
        $invariants = clone $this->ancestralInvariants;
        $invariants->add($this->invariantConditions);

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
        $result = $this->extends;

        // We got an error that this is nor array, weird but build up a final frontier here
        if (!is_array($result)) {

            $result = array($result);
        }

        return $result;
    }
}
