<?php

/**
 * \AppserverIo\Doppelgaenger\Parser\TraitParser
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

use AppserverIo\Doppelgaenger\Entities\Definitions\TraitDefinition;
use AppserverIo\Doppelgaenger\Exceptions\GeneratorException;
use AppserverIo\Psr\MetaobjectProtocol\Dbc\Annotations\Invariant;

/**
 * Parser which is used to parse trait definitions.
 * Does inherit from the class parser, as both have a lot in common
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class TraitParser extends AbstractStructureParser
{

    /**
     * We want to be able to parse properties
     */
    use PropertyParserTrait;

    /**
     * Token representing the structure this parser is used for
     *
     * @var integer TOKEN
     */
    const TOKEN = T_TRAIT;

    /**
     * Will return the token representing the structure the parser is used for e.g. T_CLASS
     *
     * @return integer
     */
    public function getToken()
    {
        return self::TOKEN;
    }

    /**
     * Will return the constants within the main token array.
     * Traits cannot have constants sadly...
     *
     * @return array
     */
    public function getConstants()
    {
        return array();
    }

    /**
     * Returns a TraitDefinition from a token array.
     *
     * This method will use a set of other methods to parse a token array and retrieve any
     * possible information from it. This information will be entered into a ClassDefinition object.
     *
     * @param array   $tokens       The token array containing structure tokens
     * @param boolean $getRecursive Do we have to get the ancestral conditions as well? Makes no sense currently
     *
     * @return \AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface
     *
     * @throws \AppserverIo\Doppelgaenger\Exceptions\GeneratorException
     */
    protected function getDefinitionFromTokens($tokens, $getRecursive = false)
    {
        // First of all we need a new TraitDefinition to fill
        if (is_null($this->currentDefinition)) {
            $this->currentDefinition = new TraitDefinition();

        } elseif (!$this->currentDefinition instanceof TraitDefinition) {
            throw new GeneratorException(sprintf(
                'The structure definition %s does not seem to be a trait definition.',
                $this->currentDefinition->getQualifiedName()
            ));
        }

        // Save the path of the original definition for later use
        $this->currentDefinition->setPath($this->file);

        // File based namespaces do not make much sense, so hand it over here.
        $this->currentDefinition->setNamespace($this->getNamespace());
        $this->currentDefinition->setName($this->getName($tokens));
        $this->currentDefinition->setUsedStructures($this->getUsedStructures());

        // For our next step we would like to get the doc comment (if any)
        $this->currentDefinition->setDocBlock($this->getDocBlock($tokens, $this->getToken()));

        // Get start and end line
        $this->currentDefinition->setStartLine($this->getStartLine($tokens));
        $this->currentDefinition->setEndLine($this->getEndLine($tokens));

        // So we got our docBlock, now we can parse the invariant annotations from it
        $annotationParser = new AnnotationParser($this->file, $this->config, $this->tokens, $this->currentDefinition);
        $this->currentDefinition->setInvariantConditions(
            $annotationParser->getConditions(
                $this->currentDefinition->getDocBlock(),
                Invariant::ANNOTATION
            )
        );

        // Only thing still missing are the methods, so ramp up our FunctionParser
        $functionParser = new FunctionParser(
            $this->file,
            $this->config,
            $this->structureDefinitionHierarchy,
            $this->structureMap,
            $this->currentDefinition,
            $this->tokens
        );

        $this->currentDefinition->setFunctionDefinitions(
            $functionParser->getDefinitionListFromTokens(
                $tokens,
                $getRecursive
            )
        );

        // Lets get the attributes the class might have
        $this->currentDefinition->setAttributeDefinitions($this->getAttributes($tokens));

        // Before exiting we will add the entry to the current structure definition hierarchy
        $this->structureDefinitionHierarchy->insert($this->currentDefinition);

        return $this->currentDefinition;
    }
}
