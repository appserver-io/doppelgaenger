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
 * @subpackage Parser
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\Parser;

use AppserverIo\Doppelgaenger\Entities\Definitions\TraitDefinition;
use AppserverIo\Doppelgaenger\Dictionaries\Annotations;

/**
 * AppserverIo\Doppelgaenger\Parser\TraitParser
 *
 * Parser which is used to parse trait definitions.
 * Does inherit from the class parser, as both have a lot in common
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Parser
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
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
     */
    protected function getDefinitionFromTokens($tokens, $getRecursive = false)
    {
        // First of all we need a new TraitDefinition to fill
        if (is_null($this->currentDefinition)) {

            $this->currentDefinition = new TraitDefinition();
        }

        // Save the path of the original definition for later use
        $this->currentDefinition->path = $this->file;

        // File based namespaces do not make much sense, so hand it over here.
        $this->currentDefinition->namespace = $this->getNamespace();
        $this->currentDefinition->name = $this->getName($tokens);
        $this->currentDefinition->usedNamespaces = $this->getUsedNamespaces();

        // For our next step we would like to get the doc comment (if any)
        $this->currentDefinition->docBlock = $this->getDocBlock($tokens, T_CLASS);

        // Lets get the attributes the class might have
        $this->currentDefinition->attributeDefinitions = $this->getAttributes(
            $tokens
        );

        // So we got our docBlock, now we can parse the invariant annotations from it
        $annotationParser = new AnnotationParser($this->file, $this->config, $this->tokens, $this->currentDefinition);
        $this->currentDefinition->invariantConditions = $annotationParser->getConditions(
            $this->currentDefinition->getDocBlock(),
            Annotations::INVARIANT
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

        $this->currentDefinition->functionDefinitions = $functionParser->getDefinitionListFromTokens(
            $tokens,
            $getRecursive
        );

        // Lets get the attributes the class might have
        $this->currentDefinition->attributeDefinitions = $this->getAttributes(
            $tokens,
            $this->currentDefinition->getInvariants()
        );

        // Lock the definition
        $this->currentDefinition->lock();

        // Before exiting we will add the entry to the current structure definition hierarchy
        $this->structureDefinitionHierarchy->insert($this->currentDefinition);

        return $this->currentDefinition;
    }
}
