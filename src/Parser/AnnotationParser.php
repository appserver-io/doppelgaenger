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

use AppserverIo\Doppelgaenger\Entities\Assertions\AssertionFactory;
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
use AppserverIo\Psr\MetaobjectProtocol\Aop\Annotations\Introduce;
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
     * @var string[] $searchedAnnotations
     */
    protected $searchedAnnotations;

    /**
     * All valid annotation types we consider complex
     *
     * @var string[] $validComplexAnnotations
     */
    protected $validComplexAnnotations = array(
        Ensures::ANNOTATION,
        Invariant::ANNOTATION,
        Requires::ANNOTATION,
        Introduce::ANNOTATION
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
     * @param string[] $annotationStrings The basic annotation to search for
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
     * DocBlock syntax is preferred
     *
     * @param string $string         String to search in
     * @param string $annotationType Name of the annotation (without the leading "@") to search for
     *
     * @return \stdClass[]
     * @throws \AppserverIo\Doppelgaenger\Exceptions\ParserException
     */
    public function getAnnotationsByType($string, $annotationType)
    {
        $collectedAnnotations = array();

        // we have to determine what type of annotations we are searching for, complex (doctrine style) or simple
        if (isset(array_flip($this->validComplexAnnotations)[$annotationType])) {
            // complex annotations are parsed using herrera-io/php-annotations

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

        } else {
            // all other annotations we would like to parse ourselves

            $rawAnnotations = array();
            preg_match_all('/@' . $annotationType . '.+?\n/s', $string, $rawAnnotations);

            // build up stdClass instances from the result
            foreach ($rawAnnotations[0] as $rawAnnotation) {
                $annotationPieces = explode('##', preg_replace('/\s+/', '##', $rawAnnotation));

                // short sanity check
                if ($annotationPieces[0] === '@' . $annotationType &&
                    is_string($annotationPieces[1]) &&
                    (is_string($annotationPieces[2]) || $annotationType === 'return')
                ) {
                    // we got at least the pieces we are searching for, but we do not care about meaning here

                    // create the class and fill it
                    $annotation = new \stdClass();
                    $annotation->name = $annotationType;
                    $annotation->values = array(
                        'operand' => empty($annotationPieces[2]) ? '' : $annotationPieces[2],
                        'typeHint' => $annotationPieces[1]
                    );

                    $collectedAnnotations[] = $annotation;

                } else {
                    // tell them we got a problem

                    throw new ParserException(
                        sprintf(
                            'Could not parse annotation %s within structure %s',
                            $rawAnnotation,
                            $this->currentDefinition->getQualifiedName()
                        )
                    );
                }
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

        // get the annotations for the passed condition keyword
        $annotations = $this->getAnnotationsByType($docBlock, $conditionKeyword);

        // if we have to enforce basic type safety we need some more annotations
        if ($this->config->getValue('enforcement/enforce-default-type-safety') === true) {
            // lets switch the

            switch ($conditionKeyword)
            {
                case Ensures::ANNOTATION:
                    // we have to consider @return annotations as well

                    $annotations = array_merge(
                        $annotations,
                        $this->getAnnotationsByType($docBlock, 'return')
                    );
                    break;

                case Requires::ANNOTATION:
                    // we have to consider @param annotations as well

                    $annotations = array_merge(
                        $annotations,
                        $this->getAnnotationsByType($docBlock, 'param')
                    );
                    break;

                default:
                    break;
            }
        }

        // lets build up the result array
        $assertionFactory = new AssertionFactory();
        $result = new AssertionList();
        foreach ($annotations as $annotation) {
            // try to create assertion instances for all annotations
            try {
                $assertion = $assertionFactory->getInstance($annotation);

            } catch (\Exception $e) {
                error_log($e->getMessage());
                continue;
            }

            if ($assertion !== false) {
                // Do we already got a private context we can set? If not we have to find out four ourselves
                if ($privateContext !== null) {
                    // Add the context (wether private or not)
                    $assertion->setPrivateContext($privateContext);

                } else {
                    // Add the context (private or not)
                    $this->determinePrivateContext($assertion);
                }

                // finally determine the minimal scope of this assertion and add it to our result
                $this->determineMinimalScope($assertion);
                $result->add($assertion);
            }
        }

        return $result;
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
