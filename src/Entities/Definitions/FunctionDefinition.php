<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition
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
use AppserverIo\Doppelgaenger\Entities\Lists\ParameterDefinitionList;
use AppserverIo\Doppelgaenger\Entities\Lists\PointcutExpressionList;
use AppserverIo\Doppelgaenger\Entities\Lists\TypedListList;
use AppserverIo\Doppelgaenger\Entities\Lists\PointcutList;
use AppserverIo\Psr\MetaobjectProtocol\Dbc\Assertions\AssertionInterface;

/**
 * Provides a definition of a (generally speaking) function.
 * This includes methods as well
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class FunctionDefinition extends AbstractDefinition
{

    /**
     * @var string $docBlock DocBlock comment of the function
     */
    protected $docBlock;

    /**
     * @var boolean $isFinal Is the function final?
     */
    protected $isFinal;

    /**
     * @var boolean $isAbstract Is the function abstract?
     */
    protected $isAbstract;

    /**
     * @var string $visibility Visibility of the method
     */
    protected $visibility;

    /**
     * @var boolean $isStatic Is the method static?
     */
    protected $isStatic;

    /**
     * @var string $name Name of the function
     */
    protected $name;

    /**
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\ParameterDefinitionList $parameterDefinitions List of parameter definitions
     */
    protected $parameterDefinitions;

    /**
     * Lists of pointcuts
     *
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\PointcutExpressionList $pointcuts
     */
    protected $pointcutExpressions;

    /**
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\AssertionList $preconditions Preconditions of this function
     */
    protected $preconditions;

    /**
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\TypedListList $ancestralPreconditions Preconditions of any parent functions
     */
    protected $ancestralPreconditions;

    /**
     * @var boolean $usesOld Does this function use the dgOld keyword?
     */
    protected $usesOld;

    /**
     * @var string $body Body of the function
     */
    protected $body;

    /**
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\AssertionList $postconditions Postconditions of this function
     */
    protected $postconditions;

    /**
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\TypedListList $ancestralPostconditions
     *          Postconditions of any parent functions
     */
    protected $ancestralPostconditions;

    /**
     * Name of the structure containing that function
     *
     * @var string $structureName
     */
    protected $structureName;

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->docBlock = '';
        $this->isFinal = false;
        $this->isAbstract = false;
        $this->visibility = '';
        $this->isStatic = false;
        $this->name = '';
        $this->parameterDefinitions = new ParameterDefinitionList();
        $this->preconditions = new AssertionList();
        $this->ancestralPreconditions = new TypedListList();
        $this->usesOld = false;
        $this->body = '';
        $this->postconditions = new AssertionList();
        $this->ancestralPostconditions = new TypedListList();
        $this->pointcutExpressions = new PointcutExpressionList();
        $this->structureName = '';
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
     * Getter method for attribute $isFinal
     *
     * @return boolean
     */
    public function isFinal()
    {
        return $this->isFinal;
    }

    /**
     * Getter method for attribute $isAbstract
     *
     * @return boolean
     */
    public function isAbstract()
    {
        return $this->isAbstract;
    }

    /**
     * Getter method for attribute $visibility
     *
     * @return string
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * Getter method for attribute $isStatic
     *
     * @return boolean
     */
    public function isStatic()
    {
        return $this->isStatic;
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
     * Getter method for attribute $parameterDefinitions
     *
     * @return ParameterDefinitionList
     */
    public function getParameterDefinitions()
    {
        return $this->parameterDefinitions;
    }

    /**
     * Getter method for attribute $preconditions
     *
     * @return AssertionList
     */
    public function getPreconditions()
    {
        return $this->preconditions;
    }

    /**
     * Getter method for attribute $ancestralPreconditions
     *
     * @return null|TypedListList
     */
    public function getAncestralPreconditions()
    {
        return $this->ancestralPreconditions;
    }

    /**
     * Getter method for attribute $body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Getter method for attribute $pointcutExpressions
     *
     * @return \AppserverIo\Doppelgaenger\Entities\Lists\PointcutExpressionList
     */
    public function getPointcutExpressions()
    {
        return $this->pointcutExpressions;
    }

    /**
     * Getter method for attribute $postconditions
     *
     * @return AssertionList
     */
    public function getPostconditions()
    {
        return $this->postconditions;
    }

    /**
     * Getter method for attribute $ancestralPostconditions
     *
     * @return null|TypedListList
     */
    public function getAncestralPostconditions()
    {
        return $this->ancestralPreconditions;
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

    /**
     * Will return all preconditions. Direct as well as ancestral.
     *
     * @param boolean $nonPrivateOnly   Make this true if you only want conditions which do not have a private context
     * @param boolean $filterMismatches Do we have to filter condition mismatches due to signature changes
     *
     * @return \AppserverIo\Doppelgaenger\Entities\Lists\TypedListList
     */
    public function getAllPreconditions($nonPrivateOnly = false, $filterMismatches = true)
    {
        $preconditions = clone $this->ancestralPreconditions;
        $preconditions->add($this->preconditions);

        // If we need to we will filter all the non private conditions from the lists
        // Preconditions have to be flattened as the signature of a function (and therefore it's parameter list)
        // might change within a structure hierarchy.
        // We have to do that here, as we cannot risk to delete conditions which use non existing parameters, as
        // a potential child method might want to inherit grandparental conditions which do not make sense for us
        // (but do for them).
        if ($nonPrivateOnly === true || $filterMismatches === true) {
            $preconditionListIterator = $preconditions->getIterator();
            foreach ($preconditionListIterator as $preconditionList) {
                $preconditionIterator = $preconditionList->getIterator();
                foreach ($preconditionIterator as $key => $precondition) {
                    // The privacy issue
                    if ($nonPrivateOnly === true && $precondition->isPrivateContext()) {
                        $preconditionList->delete($key);
                    }

                    // The mismatch filter
                    if ($filterMismatches === true && $this->conditionIsMismatch($precondition)) {
                        $preconditionList->delete($key);
                    }
                }
            }
        }

        // Return what is left
        return $preconditions;
    }

    /**
     * Will return all postconditions. Direct as well as ancestral.
     *
     * @param boolean $nonPrivateOnly Make this true if you only want conditions which do not have a private context
     *
     * @return \AppserverIo\Doppelgaenger\Entities\Lists\TypedListList
     */
    public function getAllPostconditions($nonPrivateOnly = false)
    {
        $postconditions = clone $this->ancestralPostconditions;
        $postconditions->add($this->postconditions);

        // If we need to we will filter all the non private conditions from the lists
        if ($nonPrivateOnly === true) {
            $postconditionListIterator = $postconditions->getIterator();
            foreach ($postconditionListIterator as $postconditionList) {
                $postconditionIterator = $postconditionList->getIterator();
                foreach ($postconditionIterator as $key => $postcondition) {
                    if ($postcondition->isPrivateContext()) {
                        $postconditionList->delete($key);
                    }
                }
            }
        }

        // Return what is left
        return $postconditions;
    }

    /**
     * Will return the header of this function either in calling or in defining manner.
     * String will stop after the closing ")" bracket, so the string can be used for interfaces as well.
     *
     * @param string  $type        Can be either "call" or "definition"
     * @param string  $suffix      Suffix for the function name
     * @param boolean $showMe      Will mark a method as original by extending it with a suffix
     * @param boolean $forceStatic Will force static call for call type headers
     *
     * @return  string
     */
    public function getHeader($type, $suffix = '', $showMe = false, $forceStatic = false)
    {
        $header = '';

        // We have to do some more work if we need the definition header
        if ($type === 'definition') {
            // Check for final or abstract (abstract cannot be used if final)
            if ($this->isFinal) {
                $header .= ' final ';
            } elseif ($this->isAbstract) {
                $header .= ' abstract ';
            }

            // Do we need to make this function public? If not we will use the original visibility
            if ($showMe === false) {
                // Prepend visibility
                $header .= $this->visibility;

            } else {
                $header .= 'public';
            }

            // Are we static?
            if ($this->isStatic) {
                $header .= ' static ';
            }

            // Function keyword and name
            $header .= ' function ';
        }

        // If we have to generate code for a call we have to check for either static or normal access
        if ($type === 'call') {
            if ($this->isStatic === true || $forceStatic) {
                $header .= 'self::';
            } else {
                $header .= '$this->';
            }
        }

        // Function name + the suffix we might have gotten
        $header .= $this->name . $suffix;

        // Iterate over all parameters and create the parameter string.
        // We will create the string we need, either for calling the function or for defining it.
        $parameterString = array();
        $parameterIterator = $this->parameterDefinitions->getIterator();
        for ($k = 0; $k < $parameterIterator->count(); $k++) {
            // Our parameter
            $parameter = $parameterIterator->current();

            // Fill our strings
            $parameterString[] = $parameter->getString($type);

            // Next assertion please
            $parameterIterator->next();
        }

        // Check if we even got something. If not a closure header would be malformed.
        if (!empty($parameterString)) {
            // Explode to insert commas
            $parameterString = implode(', ', $parameterString);

            // Append the parameters to the header
            $header .= '(' . $parameterString . ')';

        } else {
            $header .= '()';
        }

        return $header;
    }

    /**
     * This method will check if a certain assertion mismatches the scope of this function.
     *
     * @param \AppserverIo\Psr\MetaobjectProtocol\Dbc\Assertions\AssertionInterface $assertion The assertion to check for a possible mismatch
     *          within this function context
     *
     * @return boolean
     */
    protected function conditionIsMismatch(AssertionInterface $assertion)
    {
        // If the minimal scope is above or below function scope we cannot determine if this is a mismatch in
        // function scope.
        if ($assertion->getMinScope() !== 'function') {
            return false;
        }

        // We will get all parameters and check if we can find any of it in the assertion string.
        // If not then we have a mismatch as the condition is only function scoped
        $assertionString = $assertion->getString();
        $parameterIterator = $this->parameterDefinitions->getIterator();
        foreach ($parameterIterator as $parameter) {
            if (strpos($assertionString, $parameter->name) !== false) {
                return false;
            }
        }

        // Still here, that does not sound good
        return true;
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
     * Setter method for attribute $parameterDefinitions
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Lists\ParameterDefinitionList $parameterDefinitions List of parameters
     *
     * @return null
     */
    public function setParameterDefinitions(ParameterDefinitionList $parameterDefinitions)
    {
        $this->parameterDefinitions = $parameterDefinitions;
    }

    /**
     * Setter method for attribute $preconditions
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Lists\AssertionList $preconditions List of preconditions
     *
     * @return null
     */
    public function setPreconditions(AssertionList $preconditions)
    {
        $this->preconditions = $preconditions;
    }

    /**
     * Setter method for attribute $ancestralPreconditions
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Lists\TypedListList $ancestralPreconditions Inherited preconditions
     *
     * @return null
     */
    public function setAncestralPreconditions(TypedListList $ancestralPreconditions)
    {
        $this->ancestralPreconditions = $ancestralPreconditions;
    }

    /**
     * Setter method for attribute $pointcutExpressions
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Lists\PointcutExpressionList $pointcutExpressions List of pointcut expressions
     *
     * @return null
     */
    public function setPointcutExpressions(PointcutExpressionList $pointcutExpressions)
    {
        $this->pointcutExpressions = $pointcutExpressions;
    }

    /**
     * Getter method for attribute $postconditions
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Lists\AssertionList $postconditions List of postconditions
     *
     * @return null
     */
    public function setPostconditions(AssertionList $postconditions)
    {
        $this->postconditions = $postconditions;
    }

    /**
     * Setter method for attribute $ancestralPostconditions
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Lists\TypedListList $ancestralPreconditions Inherited preconditions
     *
     * @return null
     */
    public function setAncestralPostconditions(TypedListList $ancestralPreconditions)
    {
        $this->ancestralPreconditions = $ancestralPreconditions;
    }

    /**
     * Stter method for attribute $usesOld
     *
     * @param boolean $usesOld Does the function use the "old" keyword
     *
     * @return null
     */
    public function setUsesOld($usesOld)
    {
        $this->usesOld = $usesOld;
    }

    /**
     * Getter method for attribute $body
     *
     * @param string $body Body of the function
     *
     * @return null
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Setter method for attribute $structureName
     *
     * @param string $structureName Name of the structure containing that function
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

    /**
     * Getter method for attribute $usesOld
     *
     * @return boolean
     */
    public function usesOld()
    {
        return $this->usesOld;
    }
}
