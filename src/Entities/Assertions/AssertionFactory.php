<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Assertions\AssertionFactory
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

namespace AppserverIo\Doppelgaenger\Entities\Assertions;

use AppserverIo\Doppelgaenger\Dictionaries\ReservedKeywords;
use AppserverIo\Doppelgaenger\Entities\Lists\AssertionList;
use AppserverIo\Psr\MetaobjectProtocol\Dbc\Annotations\Ensures;
use AppserverIo\Psr\MetaobjectProtocol\Dbc\Annotations\Invariant;
use AppserverIo\Psr\MetaobjectProtocol\Dbc\Annotations\Requires;

/**
 * This class will help instantiating the right assertion class for any given assertion array
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class AssertionFactory
{

    /**
     * All simple data types which are known but are aliased without an is_... function.
     *
     * @var string[] $scalarTypeMappings
     */
    protected $scalarTypeMappings = array(
        'boolean' => 'bool',
        'void' => 'null'
    );

    /**
     * All simple data types which are supported by PHP
     * and have a is_... function.
     *
     * @var string[] $validScalarTypes
     */
    protected $validScalarTypes = array(
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
        'string',
        'boolean',
        'void'
    );

    /**
     * Parse assertions which are a collection of others
     *
     * @param string    $connective How are they combined? E.g. "||"
     * @param \stdClass $annotation The annotation to create chained assertions from
     *
     * @return \AppserverIo\Doppelgaenger\Entities\Assertions\ChainedAssertion
     */
    protected function createChainedAssertion($connective, \stdClass $annotation)
    {
        // Get all the parts of the string
        $assertionArray = explode(' ', $annotation->values['typeHint']);

        // Check all string parts for the | character
        $combinedPart = '';
        $combinedIndex = 0;
        foreach ($assertionArray as $key => $assertionPart) {
            // Check which part contains the | but does not only consist of it
            if ($this->filterOrCombinator($assertionPart) && trim($assertionPart) !== $connective) {
                $combinedPart = trim($assertionPart);
                $combinedIndex = $key;
                break;
            }
        }


        // Now we have to create all the separate assertions for each part of the $combinedPart string
        $assertionList = new AssertionList();
        foreach (explode($connective, $combinedPart) as $partString) {
            // Rebuild the assertion string with one partial string of the combined part
            $tmp = $assertionArray;
            $tmp[$combinedIndex] = $partString;
            $annotation->values['typeHint'] = $partString;
            $assertion = $this->getInstance($annotation);

            if (is_bool($assertion)) {
                continue;

            } else {
                $assertionList->add($assertion);
            }
        }

        // We got everything. Create a ChainedAssertion instance
        return new ChainedAssertion($assertionList, '||');
    }

    /**
     * Will parse assertions from a DocBlock comment piece. If $usedAnnotation is given we will concentrate on that
     * type of assertion only.
     * We might return false on error
     *
     * @param \stdClass $annotation The annotation to create simple assertions from
     *
     * @return boolean|\AppserverIo\Doppelgaenger\Interfaces\AssertionInterface
     *
     * @throws \AppserverIo\Doppelgaenger\Exceptions\ParserException
     */
    protected function createSimpleAssertion(\stdClass $annotation)
    {
        // Do we have an or connective aka "|"?
        if ($this->filterOrCombinator($annotation->values['typeHint'])) {
            // If we got invalid arguments then we will fail
            try {
                return $this->createChainedAssertion('|', $annotation);

            } catch (\InvalidArgumentException $e) {
                return false;
            }
        }

        // check what we have got
        $variable = $annotation->values['operand'];
        $type = $this->filterScalarType($annotation->values['typeHint']);
        $class = $this->filterType($annotation->values['typeHint']);
        $collectionType = $this->filterTypedCollection($annotation->values['typeHint']);

        // "mixed" is something we cannot work with, special case is a mixed typed collection which basically is an array
        if ($type === 'mixed' || $class === 'mixed') {
            return false;

        } elseif ($collectionType === 'mixed') {
            // we have a collection with mixed content, so basically an array
            $type = 'array';
            $collectionType = false;
        }

        if ($annotation->name === 'return') {
            $variable = ReservedKeywords::RESULT;
        }

        // Now we have to check what we got
        // First of all handle if we got a simple type
        if ($type !== false && !empty($type)) {
            return new TypeAssertion($variable, $type);

        } elseif ($class !== false && !empty($class)) {
            // seems we have an instance assertion here

            return new InstanceAssertion($variable, $class);

        } elseif ($collectionType !== false && !empty($collectionType)) {
            // seems we have a typed collection here

            return new TypedCollectionAssertion($variable, $collectionType);
        } else {
            return false;
        }
    }

    /**
     * Will filter for any referenced structure as a indicated type hinting of complex types
     *
     * @param string $string The string potentially containing a structure name
     *
     * @return boolean
     */
    protected function filterType($string)
    {
        // if the string is neither a scalar type, a typed collection and contains a namespace separator we assume a class

        // check if we know the simple type
        $validScalarTypes = array_flip($this->validScalarTypes);
        if (isset($validScalarTypes[$string])) {
            return false;
        }

        // check if it might be a typed collection
        foreach (array('<', '>', '[', ']') as $needle) {
            if (strpos($string, $needle) !== false) {
                return false;
            }
        }

        // check if we have a namespace separator
        if (strpos($string, '\\') !== false) {
            return $string;
        }

        // We found nothing; tell them.
        return false;
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
     * Will filter for any simple type that may be used to indicate type hinting
     *
     * @param string $string The string potentially containing a scalar type hint
     *
     * @return boolean|string
     */
    protected function filterScalarType($string)
    {
        // check if we know the simple type
        $validScalarTypes = array_flip($this->validScalarTypes);
        if (!isset($validScalarTypes[$string])) {
            return false;
        }

        // if we have a mapping we have to return the mapped value instead
        if (isset($this->scalarTypeMappings[$string])) {
            $string = $this->scalarTypeMappings[$string];
        }

        // is it a scalar type we can check for?
        if (function_exists('is_' . $string)) {
            return $string;
        }

        // We found nothing; tell them.
        return false;
    }

    /**
     * Will filter for type safe collections of the form array<Type> or Type[]
     *
     * @param string $string The string potentially containing a type hint for a typed collection
     *
     * @return boolean|string
     */
    protected function filterTypedCollection($string)
    {
        $tmp = strpos($string, 'array<');
        if ($tmp !== false) {
            // we have a Java Generics like syntax

            if (strpos($string, '>') > $tmp) {
                $stringPiece = explode('array<', $string);
                $stringPiece = $stringPiece[1];

                return strstr($stringPiece, '>', true);
            }

        } elseif (strpos($string, '[]')) {
            // we have a common <TYPE>[] syntax

            return strstr($string, '[]', true);
        }

        // We found nothing; tell them.
        return false;
    }

    /**
     * Will return an instance of an assertion fitting the passed annotation object
     *
     * @param \stdClass $annotation Annotation object to generate assertion from
     *
     * @return \AppserverIo\Doppelgaenger\Interfaces\AssertionInterface
     * @throws \Exception
     */
    public function getInstance(\stdClass $annotation)
    {
        switch ($annotation->name) {
            case Ensures::ANNOTATION:
            case Invariant::ANNOTATION:
            case Requires::ANNOTATION:
                // complex annotations leave us with two possibilities: raw or custom assertions

                if (isset($annotation->values['type'])) {
                    // we need a custom assertion here

                    $potentialAssertion = '\AppserverIo\Doppelgaenger\Entities\Assertions\\' . $annotation->values['type'] . 'Assertion';
                    if (class_exists($potentialAssertion) && isset($annotation->values['constraint'])) {
                        // we know the class! Create an instance using the passed constraint
                        /** @var \AppserverIo\Doppelgaenger\Interfaces\AssertionInterface $assertionInstance */
                        $assertionInstance = new $potentialAssertion($annotation->values['constraint']);
                        return $assertionInstance;

                    } else {
                        throw new \Exception(sprintf('Cannot create complex assertion of type %s'), $annotation->values['type']);
                    }

                } else {
                    // a RawAssertion is sufficient

                    return new RawAssertion(array_pop($annotation->values));
                }
                break;

            case 'param':
            case 'return':
                // simple assertions leave with a wide range of type assertions
                return $this->createSimpleAssertion($annotation);
                break;

            default:
                break;
        }
    }

    /**
     * Getter for all valid scalar types we can create assertions for
     *
     * @return string[]
     */
    public function getValidScalarTypes()
    {
        return $this->validScalarTypes;
    }
}
