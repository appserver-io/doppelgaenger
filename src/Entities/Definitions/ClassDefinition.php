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
 */
class ClassDefinition extends AbstractStructureDefinition
{

    /**
     * @const string TYPE The structure type
     */
    const TYPE = 'class';

    /**
     * List of introductions which are used to extend the class's characteristics
     *
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\IntroductionList
     */
    protected $introductions;

    /**
     * @var boolean $isFinal Is this a final class
     */
    protected $isFinal;

    /**
     * @var boolean $isAbstract Is this class abstract
     */
    protected $isAbstract;

    /**
     * @var string $extends Name of the parent class (if any)
     */
    protected $extends;

    /**
     * @var array $implements Array of interface names this class implements
     */
    protected $implements;

    /**
     * @var array $constants Class constants
     */
    protected $constants;

    /**
     * @var AttributeDefinitionList $attributeDefinitions List of defined attributes
     */
    protected $attributeDefinitions;

    /**
     * @var AssertionList $invariantConditions List of directly defined invariant conditions
     */
    protected $invariantConditions;

    /**
     * @var TypedListList $ancestralInvariants List of lists of any ancestral invariants
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
    public function getIsAbstract()
    {
        return $this->isAbstract;
    }

    /**
     * Getter method for attribute $isFinal
     *
     * @return bool
     */
    public function getIsFinal()
    {
        return $this->isFinal;
    }

    /**
     * Will flatten all conditions available at the time of the call.
     * That means this method will check which conditions make sense in an inheritance context and will drop the
     * others.
     * This method MUST be protected/private so it will run through \AppserverIo\Doppelgaenger\Entities\AbstractLockableEntity's
     * __call() method which will check the lock status before doing anything.
     *
     * @return bool
     */
    protected function flattenConditions()
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
}
