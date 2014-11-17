<?php
/**
 * File containing the ClassParser class
 *
 * PHP version 5
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Parser
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Parser;

use AppserverIo\Doppelgaenger\Entities\Annotations\Introduce;
use AppserverIo\Doppelgaenger\Entities\Definitions\ClassDefinition;
use AppserverIo\Doppelgaenger\Entities\Definitions\Structure;
use AppserverIo\Doppelgaenger\Entities\Introduction;
use AppserverIo\Doppelgaenger\Entities\Lists\IntroductionList;
use AppserverIo\Doppelgaenger\Dictionaries\Annotations;

/**
 * AppserverIo\Doppelgaenger\Parser\ClassParser
 *
 * This class implements the StructureParserInterface for class structures
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Parser
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class ClassParser extends AbstractStructureParser
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
    const TOKEN = T_CLASS;

    /**
     * Returns a ClassDefinition from a token array.
     *
     * This method will use a set of other methods to parse a token array and retrieve any
     * possible information from it. This information will be entered into a ClassDefinition object.
     *
     * @param array   $tokens       The token array containing structure tokens
     * @param boolean $getRecursive Do we have to get the ancestral conditions as well?
     *
     * @return \AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface
     */
    protected function getDefinitionFromTokens($tokens, $getRecursive = true)
    {
        // First of all we need a new ClassDefinition to fill
        $this->currentDefinition = new ClassDefinition();

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

        // we would be also interested in introductions
        $introductions = new IntroductionList();
        $introductionAnnotations = $annotationParser->getAnnotationsByType(
            $this->currentDefinition->getDocBlock(),
            Introduce::ANNOTATION
        );
        foreach ($introductionAnnotations as $introductionAnnotation) {

            $introduction = new Introduction();
            $introduction->target = $this->currentDefinition->getQualifiedName();
            $introduction->implementation = $introductionAnnotation->values['implementation'];
            $introduction->interface = $introductionAnnotation->values['interface'];
            $introduction->lock();

            $introductions->add($introduction);
        }

        $this->currentDefinition->introductions = $introductions;

        // Get the class identity
        $this->currentDefinition->isFinal = $this->hasSignatureToken($this->tokens, T_FINAL, T_CLASS);
        $this->currentDefinition->isAbstract = $this->hasSignatureToken($this->tokens, T_ABSTRACT, T_CLASS);

        // Lets check if there is any inheritance, or if we implement any interfaces
        $this->currentDefinition->extends = trim(
            $this->resolveUsedNamespace(
                $this->currentDefinition,
                $this->getParent($tokens)
            ),
            '\\'
        );
        // Get all the interfaces we have
        $this->currentDefinition->implements = $this->getInterfaces($this->currentDefinition);

        // Get all class constants
        $this->currentDefinition->constants = $this->getConstants($tokens);

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

        // If we have to parse the definition in a recursive manner, we have to get the parent invariants
        if ($getRecursive === true) {

            // Add all the assertions we might get from ancestral dependencies
            $this->addAncestralAssertions($this->currentDefinition);
        }

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

    /**
     * This method will add all assertions any ancestral structures (parent classes, implemented interfaces) might have
     * to the passed class definition.
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Definitions\ClassDefinition $classDefinition The class definition we have to
     *                                                                                add the assertions to
     *
     * @return null
     */
    protected function addAncestralAssertions(ClassDefinition $classDefinition)
    {
        $dependencies = $classDefinition->getDependencies();
        foreach ($dependencies as $dependency) {

            // freshly set the dependency definition to avoid side effects
            $dependencyDefinition = null;

            $fileEntry = $this->structureMap->getEntry($dependency);
            if (!$fileEntry instanceof Structure) {

                // Continue, don't fail as we might have dependencies which are not under Doppelgaenger surveillance
                continue;
            }

            // Get the needed parser
            $structureParserFactory = new StructureParserFactory();
            $parser = $structureParserFactory->getInstance(
                $fileEntry->getType(),
                $fileEntry->getPath(),
                $this->config,
                $this->structureMap,
                $this->structureDefinitionHierarchy
            );

            // Get the definition
            $dependencyDefinition = $parser->getDefinition(
                $dependency,
                true
            );

            // Only classes and traits have invariants
            if ($fileEntry->getType() === 'class') {

                $classDefinition->ancestralInvariants = $dependencyDefinition->getInvariants(true);
            }

            // Finally add the dependency definition to our structure definition hierarchy to avoid
            // redundant parsing
            $this->structureDefinitionHierarchy->insert($dependencyDefinition);
        }
    }

    /**
     * Will find the parent class we have (if any). Will return an empty string if there is none.
     *
     * @param array $tokens Array of tokens for this class
     *
     * @return string
     */
    protected function getParent(array $tokens)
    {
        // Check the tokens
        $className = '';
        for ($i = 0; $i < count($tokens); $i++) {

            // If we got the class name
            if ($tokens[$i][0] === T_EXTENDS) {

                for ($j = $i + 1; $j < count($tokens); $j++) {

                    if ($tokens[$j] === '{' || $tokens[$j][0] === T_CURLY_OPEN || $tokens[$j][0] === T_IMPLEMENTS) {

                        return $className;

                    } elseif ($tokens[$j][0] === T_STRING) {

                        $className .= $tokens[$j][1];
                    }
                }
            }
        }

        // Return what we did or did not found
        return $className;
    }

    /**
     * Will return an array containing all interfaces this class implements
     *
     * @param ClassDefinition $classDefinition Reference of class definition so we can resolve the namespaces
     *
     * @return array
     */
    protected function getInterfaces(ClassDefinition & $classDefinition)
    {
        // Check the tokens
        $interfaces = array();
        for ($i = 0; $i < $this->tokenCount; $i++) {

            // If we got the class name
            if ($this->tokens[$i][0] === T_IMPLEMENTS) {

                for ($j = $i + 1; $j < $this->tokenCount; $j++) {

                    if ($this->tokens[$j] === '{' || $this->tokens[$j][0] === T_CURLY_OPEN ||
                        $this->tokens[$j][0] === T_EXTENDS
                    ) {

                        return $interfaces;

                    } elseif ($this->tokens[$j][0] === T_STRING) {

                        $interfaces[] = $this->resolveUsedNamespace(
                            $classDefinition,
                            $this->tokens[$j][1]
                        );
                    }
                }
            }
        }

        // Return what we did or did not found
        return $interfaces;
    }
}
