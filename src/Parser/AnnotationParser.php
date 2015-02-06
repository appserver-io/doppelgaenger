<?php

/**
 * \AppserverIo\Doppelgaenger\Parser\AnnotationParser
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

namespace AppserverIo\Doppelgaenger\Parser;

use AppserverIo\Doppelgaenger\Entities\Assertions\RawAssertion;
use AppserverIo\Doppelgaenger\Entities\Assertions\TypedCollectionAssertion;
use AppserverIo\Doppelgaenger\Entities\Definitions\AttributeDefinition;
use AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition;
use AppserverIo\Doppelgaenger\Entities\Joinpoint;
use AppserverIo\Doppelgaenger\Entities\Lists\AssertionList;
use AppserverIo\Doppelgaenger\Entities\Assertions\ChainedAssertion;
use AppserverIo\Doppelgaenger\Config;
use AppserverIo\Doppelgaenger\Entities\Lists\PointcutExpressionList;
use AppserverIo\Doppelgaenger\Entities\PointcutExpression;
use AppserverIo\Doppelgaenger\Exceptions\ParserException;
use AppserverIo\Doppelgaenger\Interfaces\AssertionInterface;
use AppserverIo\Doppelgaenger\Interfaces\PropertiedStructureInterface;
use AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface;
use AppserverIo\Doppelgaenger\Dictionaries\ReservedKeywords;
use AppserverIo\Psr\MetaobjectProtocol\Dbc\Annotations\Ensures;
use AppserverIo\Psr\MetaobjectProtocol\Dbc\Annotations\Invariant;
use AppserverIo\Psr\MetaobjectProtocol\Dbc\Annotations\Requires;
use Herrera\Annotations\Tokenizer;
use Herrera\Annotations\Tokens;
use Herrera\Annotations\Convert\ToArray;

/**
 * The AnnotationParser class which is used to get all usable parts from within DocBlock annotation
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class AnnotationParser extends AbstractParser
{
    /**
     * The configuration aspect we need here
     *
     * @var \AppserverIo\Doppelgaenger\Config $config
     */
    protected $config;

    /**
     * The annotations which the parser will look for
     *
     * @var array $searchedAnnotations
     */
    protected $searchedAnnotations;

    /**
     * All simple data types which are known but are aliased without an is_... function.
     *
     * @var array $simpleTypeMappings
     */
    protected $simpleTypeMappings = array(
        'boolean' => 'bool',
        'void' => 'null'
    );

    /**
     * All simple data types which are supported by PHP
     * and have a is_... function.
     *
     * @var array $validSimpleTypes
     */
    protected $validSimpleTypes = array(
        'array',
        'bool',
        'callable',
        'double',
        'float',
        'int',
        'integer',
        'long',
        'null',
        'numeric',
        'object',
        'real',
        'resource',
        'scalar',
        'string'
    );

    /**
     * Default constructor
     *
     * @param string                                                                  $file              The path of the file we want to parse
     * @param \AppserverIo\Doppelgaenger\Config                                       $config            Configuration
     * @param array                                                                   $tokens            The array of tokens taken from the file
     * @param \AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface|null $currentDefinition The current definition we are working on
     */
    public function __construct(
        $file,
        Config $config,
        array & $tokens = array(),
        StructureDefinitionInterface $currentDefinition = null
    ) {
        $this->config = $config;

        parent::__construct($file, $config, null, null, $currentDefinition, $tokens);
    }

    /**
     * Will add an annotation which the parser will then look for on its next run
     *
     * @param string $annotationString The basic annotation to search for
     *
     * @return null|false
     */
    public function addAnnotation($annotationString)
    {
        // we rely on a leading "@" symbol, so sanitize the input
        if (!is_string($annotationString)) {
            return false;

        } elseif (substr($annotationString, 0, 1) === '@') {
            $annotationString = '@' . $annotationString;
        }

        $this->searchedAnnotations[] = $annotationString;
    }

    /**
     * Will add an array of annotations which the parser will then look for on its next run
     *
     * @param array<string> $annotationStrings The basic annotation to search for
     *
     * @return null
     */
    public function addAnnotations(array $annotationStrings)
    {
        foreach ($annotationStrings as $annotationString) {
            $this->addAnnotation($annotationString);
        }
    }

    /**
     * Will return an array containing all annotations of a certain type which where found within a given string
     * DocBlock syntax is prefered
     *
     * @param string $string         String to search in
     * @param string $annotationType Name of the annotation (without the leading "@") to search for
     *
     * @return array<\stdClass>
     */
    public function getAnnotationsByType($string, $annotationType)
    {
        $collectedAnnotations = array();

        // get our tokenizer and parse the doc Block
        $tokenizer = new Tokenizer();
        $tokens = new Tokens($tokenizer->parse($string));

        // convert to array and run it through our advice factory
        $toArray = new ToArray();
        $annotations = $toArray->convert($tokens);

        // only collect annotations we want
        foreach ($annotations as $annotation) {
            if ($annotation->name === $annotationType) {
                $collectedAnnotations[] = $annotation;
            }
        }

        return $collectedAnnotations;
    }

    /**
     * Will return one pointcut which does specifically only match the joinpoints of the structure
     * which this docblock belongs to
     *
     * @param string $docBlock   The DocBlock to search in
     * @param string $targetType Type of the target any resulting joinpoints have, e.g. Joinpoint::TARGET_METHOD
     * @param string $targetName Name of the target any resulting joinpoints have
     *
     * @return \AppserverIo\Doppelgaenger\Entities\Lists\PointcutExpressionList
     */
    public function getPointcutExpressions($docBlock, $targetType, $targetName)
    {
        $pointcutExpressions = new PointcutExpressionList();

        // get our tokenizer and parse the doc Block
        $tokenizer = new Tokenizer();

        $tokenizer->ignore(
            array(
                'param',
                'return',
                'throws'
            )
        );
        $tokens = new Tokens($tokenizer->parse($docBlock));

        // convert to array and run it through our advice factory
        $toArray = new ToArray();
        $annotations = $toArray->convert($tokens);

        // create the entities for the join-points and advices the pointcut describes
        foreach ($annotations as $annotation) {
            // filter out the annotations which are no proper join-points
            if (!class_exists('\AppserverIo\Psr\MetaobjectProtocol\Aop\Annotations\Advices\\' . $annotation->name)) {
                continue;
            }

            // build the join-point
            $joinpoint = new Joinpoint();
            $joinpoint->setTarget($targetType);
            $joinpoint->setCodeHook($annotation->name);
            $joinpoint->setStructure($this->currentDefinition->getQualifiedName());
            $joinpoint->setTargetName($targetName);

            // build the pointcut(s)
            foreach ($annotation->values as $rawAdvice) {
                // as it might be an array we have to sanitize it first
                if (!is_array($rawAdvice)) {
                    $rawAdvice = array($rawAdvice);
                }
                foreach ($rawAdvice as $adviceString) {
                    // create the pointcut
                    $pointcutExpression = new PointcutExpression($adviceString);
                    $pointcutExpression->setJoinpoint($joinpoint);

                    $pointcutExpressions->add($pointcutExpression);
                }
            }
        }

        return $pointcutExpressions;
    }

    /**
     * Will get the conditions for a certain assertion indicating keyword like @requires or, if configured, @param
     *
     * @param string       $docBlock         The DocBlock to search in
     * @param string       $conditionKeyword The keyword we are searching for, use assertion defining tags here!
     * @param boolean|null $privateContext   If we have to mark the parsed annotations as having a private context
     *                                       as we would have trouble finding out for ourselves.
     *
     * @return boolean|\AppserverIo\Doppelgaenger\Entities\Lists\AssertionList
     */
    public function getConditions($docBlock, $conditionKeyword, $privateContext = null)
    {
        // There are only 3 valid condition types
        if ($conditionKeyword !== Requires::ANNOTATION && $conditionKeyword !== Ensures::ANNOTATION
            && $conditionKeyword !== Invariant::ANNOTATION
        ) {
            return false;
        }

        // Get our conditions
        $rawConditions = array();
        if ($conditionKeyword === Ensures::ANNOTATION) {
            // Check if we need @return as well
            if ($this->config->getValue('enforcement/enforce-default-type-safety') === true) {
                $regex = '/' . str_replace('\\', '\\\\', $conditionKeyword) . '.+?\n|' . '@return' . '.+?\n/s';

            } else {
                $regex = '/' . str_replace('\\', '\\\\', $conditionKeyword) . '.+?\n/s';
            }

            preg_match_all($regex, $docBlock, $rawConditions);

        } elseif ($conditionKeyword === Requires::ANNOTATION) {
            // Check if we need @return as well
            if ($this->config->getValue('enforcement/enforce-default-type-safety') === true) {
                $regex = '/' . str_replace('\\', '\\\\', $conditionKeyword) . '.+?\n|' . '@param' . '.+?\n/s';

            } else {
                $regex = '/' . str_replace('\\', '\\\\', $conditionKeyword) . '.+?\n/s';
            }

            preg_match_all($regex, $docBlock, $rawConditions);

        } else {
            preg_match_all('/' . str_replace('\\', '\\\\', $conditionKeyword) . '.+?\n/s', $docBlock, $rawConditions);
        }

        // Lets build up the result array
        $result = new AssertionList();
        if (empty($rawConditions) === false) {
            foreach ($rawConditions[0] as $condition) {
                $assertion = $this->parseAssertion($condition);
                if ($assertion !== false) {
                    // Do we already got a private context we can set? If not we have to find out four ourselves
                    if ($privateContext !== null) {
                        // Add the context (wether private or not)
                        $assertion->setPrivateContext($privateContext);

                    } else {
                        // Add the context (private or not)
                        $this->determinePrivateContext($assertion);
                    }

                    // Determine the minimal scope of this assertion
                    $this->determineMinimalScope($assertion);

                    $result->add($assertion);
                }
            }
        }

        return $result;
    }

    /**
     * Will parse assertions from a DocBlock comment piece. If $usedAnnotation is given we will concentrate on that
     * type of assertion only.
     * We might return false on error
     *
     * @param string      $docString      The DocBlock piece to search in
     * @param null|string $usedAnnotation The annotation we want to specifically search for
     *
     * @return boolean|\AppserverIo\Doppelgaenger\Interfaces\AssertionInterface
     *
     * TODO we need an assertion factory badly! This is way to long
     */
    protected function parseAssertion($docString, $usedAnnotation = null)
    {
        if ($usedAnnotation === null) {
            // We have to differ between several types of assertions, so lets check which one we got
            $annotations = array('@param', '@return', Ensures::ANNOTATION, Requires::ANNOTATION, Invariant::ANNOTATION);

            $usedAnnotation = '';
            foreach ($annotations as $annotation) {
                if (strpos($docString, $annotation) !== false) {
                    $usedAnnotation = $annotation;
                    break;
                }
            }
        }

        // Do we have an or combinator aka |?
        if ($this->filterOrCombinator($docString)) {
            // If we got invalid arguments then we will fail
            try {
                return $this->parseChainedAssertion('|', $docString);

            } catch (\InvalidArgumentException $e) {
                return false;
            }
        }

        // If we got invalid arguments then we will fail
        try {
            $variable = $this->filterVariable($docString);
            $type = $this->filterType($docString);
            $class = $this->filterClass($docString);

        } catch (\InvalidArgumentException $e) {
            return false;
        }

        $assertion = false;
        switch ($usedAnnotation) {
            // We got something which can only contain type information
            case '@param':
            case '@return':

                if ($usedAnnotation === '@return') {
                    $variable = ReservedKeywords::RESULT;
                }

                // Now we have to check what we got
                // First of all handle if we got a simple type
                if ($type !== false && !empty($type)) {
                    $assertionType = 'AppserverIo\Doppelgaenger\Entities\Assertions\TypeAssertion';

                } elseif ($class !== false && !empty($class)) {
                    // We might also have a typed collection
                    $type = $this->filterTypedCollection($class);
                    if ($type !== false && $variable !== false) {
                        $assertion = new TypedCollectionAssertion($variable, $type);
                        break;
                    }

                    $type = $class;
                    $assertionType = 'AppserverIo\Doppelgaenger\Entities\Assertions\InstanceAssertion';

                } else {
                    return false;
                }

                // We handled what kind of assertion we need, now check what we will assert
                if ($variable !== false) {
                    $assertion = new $assertionType($variable, $type);

                } elseif ($usedAnnotation === '@return') {
                    $assertion = new $assertionType(ReservedKeywords::RESULT, $type);

                } else {
                    return false;
                }

                break;

            // We got our own definitions. Could be a bit more complex here
            case Requires::ANNOTATION:
            case Ensures::ANNOTATION:
            case Invariant::ANNOTATION:

                // Now we have to check what we got
                // First of all handle if we got a simple type
                if ($type !== false) {
                    $assertionType = 'AppserverIo\Doppelgaenger\Entities\Assertions\TypeAssertion';

                } elseif ($class !== false && !empty($class)) {
                    // We might also have a typed collection
                    $type = $this->filterTypedCollection($docString);
                    if ($type !== false && $variable !== false) {
                        $assertion = new TypedCollectionAssertion($variable, $type);
                        break;
                    }

                    $type = $class;
                    $assertionType = 'AppserverIo\Doppelgaenger\Entities\Assertions\InstanceAssertion';

                } else {
                    $assertion = new RawAssertion(trim(str_replace($usedAnnotation, '', $docString)));
                    break;
                }

                // We handled what kind of assertion we need, now check what we will assert
                if ($variable !== false && !empty($assertionType)) {
                    $assertion = new $assertionType($variable, $type);

                } else {
                    $assertion = new RawAssertion(trim(str_replace($usedAnnotation, '', $docString)));
                }

                break;
        }

        return $assertion;
    }

    /**
     * Parse assertions which are a collection of others
     *
     * @param string $combinator How are they combinded? E.g. "||"
     * @param string $docString  The DocBlock piece to search in
     *
     * @throws \AppserverIo\Doppelgaenger\Exceptions\ParserException
     *
     * @return \AppserverIo\Doppelgaenger\Entities\Assertions\ChainedAssertion
     */
    protected function parseChainedAssertion($combinator, $docString)
    {
        // Get all the parts of the string
        $assertionArray = explode(' ', $docString);

        // Check all string parts for the | character
        $combinedPart = '';
        $combinedIndex = 0;
        foreach ($assertionArray as $key => $assertionPart) {
            // Check which part contains the | but does not only consist of it
            if ($this->filterOrCombinator($assertionPart) && trim($assertionPart) !== '|') {
                $combinedPart = trim($assertionPart);
                $combinedIndex = $key;
                break;
            }
        }

        // Check if we got anything of value
        if (empty($combinedPart)) {
            throw new ParserException(sprintf('Error parsing what seems to be a |-combined assertion %s', $docString));
        }

        // Now we have to create all the separate assertions for each part of the $combinedPart string
        $assertionList = new AssertionList();
        foreach (explode('|', $combinedPart) as $partString) {
            // Rebuild the assertion string with one partial string of the combined part
            $tmp = $assertionArray;
            $tmp[$combinedIndex] = $partString;
            $assertion = $this->parseAssertion(implode(' ', $tmp));

            if (is_bool($assertion)) {
                continue;

            } else {
                $assertionList->add($assertion);
            }
            $assertion = false;
        }

        // We got everything. Create a ChainedAssertion instance
        return new ChainedAssertion($assertionList, '||');
    }

    /**
     * Will filter the variable used within a DocBlock piece
     *
     * @param string $docString The DocBlock piece to search in
     *
     * @return boolean|string
     */
    protected function filterVariable($docString)
    {
        // Explode the string to get the different pieces
        $explodedString = explode(' ', $docString);

        // Filter for the first variable. The first as there might be a variable name in any following description
        foreach ($explodedString as $stringPiece) {
            // Check if we got a variable
            $stringPiece = trim($stringPiece);
            $dollarPosition = strpos(
                $stringPiece,
                '$'
            );

            if ($dollarPosition === 0 || $stringPiece === ReservedKeywords::RESULT || $stringPiece === ReservedKeywords::OLD
            ) {
                return $stringPiece;
            }
        }

        // We found nothing; tell them.
        return false;
    }

    /**
     * Will filter all method calls from within the assertion string
     *
     * @param string $docString The DocBlock piece to search in
     *
     * @return array
     */
    protected function filterMethodCalls($docString)
    {
        // We will be regex ninjas here
        preg_match_all('/->(.*?)\(/', $docString, $results);

        // Return the clean output
        return $results[1];
    }

    /**
     * Will filter all attributes which are used within an assertion string
     *
     * @param string $docString The DocBlock piece to search in
     *
     * @return array
     */
    protected function filterAttributes($docString)
    {
        // We will be regex ninjas here
        preg_match_all('/(this->|self::)([a-zA-Z0-9_]*?)[=!\s<>,\)\[\]]/', $docString, $tmp);

        $results = array();
        foreach ($tmp[2] as $rawAttribute) {
            $results[] = '$' . $rawAttribute;
        }

        // Return the clean output
        return $results;
    }

    /**
     * Will filter any combinator defining a logical or-relation
     *
     * @param string $docString The DocBlock piece to search in
     *
     * @return boolean
     */
    protected function filterOrCombinator($docString)
    {
        if (strpos($docString, '|')) {
            return true;
        }

        // We found nothing; tell them.
        return false;
    }

    /**
     * Will filter for Java Generics like type safe collections of the form array<Type>
     *
     * @param string $docString The DocBlock piece to search in
     *
     * @return boolean|string
     */
    protected function filterTypedCollection($docString)
    {
        $tmp = strpos($docString, 'array<');
        if ($tmp !== false) {
            // we have a Java Generics like syntax

            if (strpos($docString, '>') > $tmp) {
                $stringPiece = explode('array<', $docString);
                $stringPiece = $stringPiece[1];

                return strstr($stringPiece, '>', true);
            }

        } elseif (strpos($docString, '[]')) {
            // we have a common <TYPE>[] syntax

            return strstr($docString, '[]', true);
        }

        // We found nothing; tell them.
        return false;
    }

    /**
     * Will filter for any simple type that may be used to indicate type hinting
     *
     * @param string $docString The DocBlock piece to search in
     *
     * @throws \InvalidArgumentException
     *
     * @return boolean|string
     */
    protected function filterType($docString)
    {
        // Explode the string to get the different pieces
        $explodedString = explode(' ', $docString);

        // Filter for the first variable. The first as there might be a variable name in any following description
        $validTypes = array_flip($this->validSimpleTypes);
        foreach ($explodedString as $stringPiece) {
            // If we got a variable before any type we do not have proper doc syntax
            if (strpos($stringPiece, '$') !== false) {
                return false;
            }

            // Check if we got a type we recognize
            $stringPiece = strtolower(trim($stringPiece));
            if (isset($validTypes[$stringPiece])) {
                return $stringPiece;

            } elseif (isset($this->simpleTypeMappings[$stringPiece])) {
                return $this->simpleTypeMappings[$stringPiece];

            } elseif ($stringPiece === 'mixed') {
                throw new \InvalidArgumentException;
            }
        }

        // We found nothing; tell them.
        return false;
    }

    /**
     * Will filter for any referenced structure as a indicated type hinting of complex types
     *
     * @param string $docString The DocBlock piece to search in
     *
     * @throws \InvalidArgumentException
     *
     * @return boolean
     */
    protected function filterClass($docString)
    {
        // Explode the string to get the different pieces
        $explodedString = explode(' ', $docString);

        // Check if we got a valid docsting, if so the first part must begin with @
        if (strpos($explodedString[0], '@') !== 0) {
            return false;
        }

        // We assume we got a class if the second part is no scalar type and no variable
        $validTypes = array_flip($this->validSimpleTypes);
        $stringPiece = trim($explodedString[1]);
        if (strpos($stringPiece, '$') === false && !isset($validTypes[strtolower($stringPiece)])) {
            // If we got "void" we do not need to bother
            if ($stringPiece !== 'void') {
                return $stringPiece;
            }
        }

        // We found nothing; tell them.
        return false;
    }

    /**
     * Will try to figure out if the passed assertion has a private context or not.
     * This information will be entered into the assertion which will then be returned.
     *
     * @param \AppserverIo\Doppelgaenger\Interfaces\AssertionInterface $assertion The assertion we need the context for
     *
     * @return void
     */
    protected function determinePrivateContext(AssertionInterface $assertion)
    {
        // we only have to act if the current definition has functions and properties
        if (!$this->currentDefinition instanceof PropertiedStructureInterface || !$this->currentDefinition instanceof StructureDefinitionInterface) {
            return;
        }

        // Get the string to check for dynamic properties
        $assertionString = $assertion->getString();

        // Do we have method calls?
        $methodCalls = $this->filterMethodCalls($assertionString);

        if (!empty($methodCalls)) {
            // Iterate over all method calls and check if they are private
            foreach ($methodCalls as $methodCall) {
                // Get the function definition, but do not get recursive conditions
                $functionDefinition = $this->currentDefinition->getFunctionDefinitions()->get($methodCall);

                // If we found something private we can end here
                if ($functionDefinition instanceof FunctionDefinition &&
                    $functionDefinition->getVisibility() === 'private'
                ) {
                    // Set the private context to true and return it
                    $assertion->setPrivateContext(true);

                    return;
                }
            }
        }

        // Do we have any attributes?
        $attributes = $this->filterAttributes($assertionString);

        if (!empty($attributes)) {
            // Iterate over all attributes and check if they are private
            foreach ($attributes as $attribute) {
                $attributeDefinition = $this->currentDefinition->getAttributeDefinitions()->get($attribute);

                // If we found something private we can end here
                if ($attributeDefinition instanceof AttributeDefinition &&
                    $attributeDefinition->getVisibility() === 'private'
                ) {
                    // Set the private context to true and return it
                    $assertion->setPrivateContext(true);

                    return;
                }
            }
        }
    }

    /**
     * Will try to figure out if the passed assertion has a private context or not.
     * This information will be entered into the assertion which will then be returned.
     *
     * @param \AppserverIo\Doppelgaenger\Interfaces\AssertionInterface $assertion The assertion we need the minimal scope for
     *
     * @return void
     */
    protected function determineMinimalScope(AssertionInterface $assertion)
    {
        // Get the string to check for dynamic properties
        $assertionString = $assertion->getString();

        // Do we have method calls? If so we have at least structure scope
        $methodCalls = $this->filterMethodCalls($assertionString);
        if (!empty($methodCalls)) {
            $assertion->setMinScope('structure');
        }

        // Do we have any attributes? If so we have at least structure scope
        $attributes = $this->filterAttributes($assertionString);
        if (!empty($attributes)) {
            $assertion->setMinScope('structure');
        }
    }
}
