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
use AppserverIo\Doppelgaenger\Entities\Lists\IntroductionList;
use AppserverIo\Doppelgaenger\Entities\Lists\TypedListList;
use AppserverIo\Doppelgaenger\Interfaces\PropertiedStructureInterface;

/**
 * AppserverIo\Doppelgaenger\Entities\Definitions\ClassDefinition
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
 * @property \AppserverIo\Doppelgaenger\Entities\Lists\IntroductionList        $introductions        List of introductions
 * @property boolean                                                           $isFinal              Is this a final class
 * @property boolean                                                           $isAbstract           Is this class abstract
 * @property string                                                            $extends              Name of the parent class (if any)
 * @property array                                                             $implements           Array of interface names this class implements
 * @property array                                                             $constants            Class constants
 * @property \AppserverIo\Doppelgaenger\Entities\Lists\AttributeDefinitionList $attributeDefinitions List of defined attributes
 * @property \AppserverIo\Doppelgaenger\Entities\Lists\AssertionList           $invariantConditions  List of directly defined invariant conditions
 * @property \AppserverIo\Doppelgaenger\Entities\Lists\TypedListList           $ancestralInvariants  List of lists of any ancestral invariants
 */
class ClassDefinition extends AbstractStructureDefinition implements PropertiedStructureInterface
{

    /**
     * @const string TYPE The structure type
     */
    const TYPE = 'class';

    /**
     * List of introductions which are used to extend the class's characteristics
     *
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\IntroductionList $introductions
     */
    protected $introductions;

    /**
     * Is this a final class
     *
     * @var boolean $isFinal
     */
    protected $isFinal;

    /**
     * Is this class abstract
     *
     * @var boolean $isAbstract
     */
    protected $isAbstract;

    /**
     * Name of the parent class (if any)
     *
     * @var string $extends
     */
    protected $extends;

    /**
     * Array of interface names this class implements
     *
     * @var array $implements
     */
    protected $implements;

    /**
     * Class constants
     *
     * @var array $constants
     */
    protected $constants;

    /**
     * List of defined attributes
     *
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\AttributeDefinitionList $attributeDefinitions
     */
    protected $attributeDefinitions;

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
     * Default constructor
     *
     * @param string $path                 File path to the class definition
     * @param string $namespace            The namespace the class belongs to
     * @param array  $usedNamespaces       All classes which are referenced by the "use" keyword
     * @param string $docBlock             The initial class docblock header
     * @param null   $introductions        List of introductions defined in the docblock
     * @param bool   $isFinal              Is this a final class
     * @param bool   $isAbstract           Is this class abstract
     * @param string $name                 Name of the class
     * @param string $extends              Name of the parent class (if any)
     * @param array  $implements           Array of interface names this class implements
     * @param array  $constants            Class constants
     * @param null   $attributeDefinitions List of defined attributes
     * @param null   $invariantConditions  List of directly defined invariant conditions
     * @param null   $ancestralInvariants  List of lists of any ancestral invariants
     * @param null   $functionDefinitions  List of methods this class defines
     */
    public function __construct(
        $path = '',
        $namespace = '',
        $usedNamespaces = array(),
        $docBlock = '',
        $introductions = null,
        $isFinal = false,
        $isAbstract = false,
        $name = '',
        $extends = '',
        $implements = array(),
        $constants = array(),
        $attributeDefinitions = null,
        $invariantConditions = null,
        $ancestralInvariants = null,
        $functionDefinitions = null
    ) {
        $this->path = $path;
        $this->namespace = $namespace;
        $this->usedNamespaces = $usedNamespaces;
        $this->docBlock = $docBlock;
        $this->introductions = is_null($introductions) ? new IntroductionList() : $introductions;
        $this->isFinal = $isFinal;
        $this->isAbstract = $isAbstract;
        $this->name = $name;
        $this->extends = $extends;
        $this->implements = $implements;
        $this->constants = $constants;
        $this->attributeDefinitions = is_null(
            $attributeDefinitions
        ) ? new AttributeDefinitionList() : $attributeDefinitions;
        $this->invariantConditions = is_null($invariantConditions) ? new AssertionList() : $invariantConditions;
        $this->ancestralInvariants = is_null($ancestralInvariants) ? new TypedListList() : $ancestralInvariants;
        $this->functionDefinitions = is_null(
            $functionDefinitions
        ) ? new FunctionDefinitionList() : $functionDefinitions;
    }

    /**
     * Will flatten all conditions available at the time of the call.
     * That means this method will check which conditions make sense in an inheritance context and will drop the
     * others.
     *
     * @return bool
     */
    public function flattenConditions()
    {
        // As our lists only supports unique entries anyway, the only thing left is to check if the condition's
        // assertions can be fulfilled (would be possible as direct assertions), and flatten the contained
        // function definitions as well
        $ancestralConditionIterator = $this->ancestralInvariants->getIterator();
        foreach ($ancestralConditionIterator as $conditionList) {

            $conditionListIterator = $conditionList->getIterator();
            foreach ($conditionListIterator as $assertion) {

            }
        }

        // No flatten all the function definitions we got
        $functionDefinitionIterator = $this->functionDefinitions->getIterator();
        foreach ($functionDefinitionIterator as $functionDefinition) {

            $functionDefinition->flattenConditions();
        }

        return false;
    }

    /**
     * Getter method for attribute $ancestralInvariants
     *
     * @return null|TypedListList
     */
    public function getAncestralInvariants()
    {
        return $this->ancestralInvariants;
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
     * Getter method for attribute $extends
     *
     * @return string
     */
    public function getExtends()
    {
        return $this->extends;
    }

    /**
     * Getter method for attribute $implements
     *
     * @return array
     */
    public function getImplements()
    {
        return $this->implements;
    }

    /**
     * Getter method for attribute $introductions
     *
     * @return null|\AppserverIo\Doppelgaenger\Entities\Lists\IntroductionList
     */
    public function getIntroductions()
    {
        return $this->introductions;
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
     * Getter method for attribute $isAbstract
     *
     * @return bool
     */
    public function isAbstract()
    {
        return $this->isAbstract;
    }

    /**
     * Getter method for attribute $isFinal
     *
     * @return bool
     */
    public function isFinal()
    {
        return $this->isFinal;
    }

    /**
     * Setter method for attribute $ancestralInvariants
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Lists\AssertionList $ancestralInvariants Inherited invariant assertions
     *
     * @return null
     */
    public function setAncestralInvariants(AssertionList $ancestralInvariants)
    {
        $this->ancestralInvariants = $ancestralInvariants;
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
     * Setter method for the $extends property
     *
     * @param string $extends Potential parent class
     *
     * @return null
     */
    public function setExtends($extends)
    {
        $this->extends = $extends;
    }

    /**
     * Getter method for the $implements property
     *
     * @param array $implements Array of interfaces the class implements
     *
     * @return null
     */
    public function setImplements($implements)
    {
        $this->implements = $implements;
    }

    /**
     * Setter method for attribute $introductions
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Lists\IntroductionList $introductions List of introductions
     *
     * @return null
     */
    public function setIntroductions(IntroductionList $introductions)
    {
        $this->introductions = $introductions;
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

    /**
     * Setter method for the $isAbstract property
     *
     * @param boolean $isAbstract If the class is abstract
     *
     * @return null
     */
    public function setIsAbstract($isAbstract)
    {
        $this->isAbstract = $isAbstract;
    }

    /**
     * Setter method for the $isFinal property
     *
     * @param boolean $isFinal If the class is defined final
     *
     * @return null
     */
    public function setIsFinal($isFinal)
    {
        $this->isFinal = $isFinal;
    }
}
