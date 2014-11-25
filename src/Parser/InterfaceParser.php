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

use AppserverIo\Doppelgaenger\Entities\Definitions\InterfaceDefinition;
use AppserverIo\Doppelgaenger\Entities\Definitions\FileDefinition;
use AppserverIo\Doppelgaenger\Entities\Lists\StructureDefinitionList;
use AppserverIo\Doppelgaenger\Dictionaries\Annotations;
use AppserverIo\Doppelgaenger\Exceptions\GeneratorException;

/**
 * AppserverIo\Doppelgaenger\Parser\InterfaceParser
 *
 * The InterfaceParser class which is used to get an \AppserverIo\Doppelgaenger\Entities\Definitions\InterfaceDefinition
 * instance (or several) from a fail containing those definition(s)
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Parser
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class InterfaceParser extends AbstractStructureParser
{

    /**
     * Token representing the structure this parser is used for
     *
     * @var integer TOKEN
     */
    const TOKEN = T_INTERFACE;

    /**
     * Will get all parent interfaces (if any).
     * Might return false on error
     *
     * @param array $tokens The token array
     *
     * @return array|boolean
     */
    public function getParents($tokens)
    {
        // Check the tokens
        $interfaceString = '';
        for ($i = 0; $i < count($tokens); $i++) {

            // If we got the interface name
            if ($tokens[$i][0] === T_EXTENDS) {

                for ($j = $i + 1; $j < count($tokens); $j++) {

                    if ($tokens[$j] === '{') {

                        // We got everything
                        break;

                    } elseif ($tokens[$j][0] === T_STRING) {

                        $interfaceString .= $tokens[$j][1];
                    }
                }
            }
        }

        // Normally we will have one or several interface names separated by commas
        $parents = explode(',', $interfaceString);

        // Did we get something useful?
        if (is_array($parents)) {

            foreach ($parents as $key => $parent) {

                $parents[$key] = trim($parent);

                // We do not want empty stuff
                if (empty($parents[$key])) {

                    unset($parents[$key]);
                }
            }

            return $parents;

        } else {

            return false;
        }
    }

    /**
     * Returns a ClassDefinition from a token array.
     *
     * This method will use a set of other methods to parse a token array and retrieve any
     * possible information from it. This information will be entered into a ClassDefinition object.
     *
     * @param array   $tokens       The token array
     * @param boolean $getRecursive Do we have to load the inherited contracts as well?
     *
     * @return \AppserverIo\Doppelgaenger\Entities\Definitions\InterfaceDefinition
     *
     * @throws \AppserverIo\Doppelgaenger\Exceptions\GeneratorException
     */
    protected function getDefinitionFromTokens($tokens, $getRecursive = true)
    {
        // First of all we need a new InterfaceDefinition to fill
        if (is_null($this->currentDefinition)) {

            $this->currentDefinition = new InterfaceDefinition();

        } elseif (!$this->currentDefinition instanceof InterfaceDefinition) {

            throw new GeneratorException(sprintf(
                'The structure definition %s does not seem to be a trait definition.',
                $this->currentDefinition->getQualifiedName()
            ));
        }

        // Save the path of the original definition for later use
        $this->currentDefinition->path = $this->file;

        // Get the interfaces own namespace and the namespace which are included via use
        $this->currentDefinition->namespace = $this->getNamespace();
        $this->currentDefinition->usedNamespaces = $this->getUsedNamespaces();

        // For our next step we would like to get the doc comment (if any)
        $this->currentDefinition->docBlock = $this->getDocBlock($tokens, T_INTERFACE);

        // Get the interface identity
        $this->currentDefinition->name = $this->getName($tokens);

        // So we got our docBlock, now we can parse the invariant annotations from it
        $annotationParser = new AnnotationParser($this->file, $this->config, $this->tokens);
        $this->currentDefinition->invariantConditions = $annotationParser->getConditions(
            $this->currentDefinition->docBlock,
            Annotations::INVARIANT
        );

        // Lets check if there is any inheritance, or if we implement any interfaces
        $parentNames = $this->getParents($tokens);
        if (count($this->currentDefinition->usedNamespaces) === 0) {

            foreach ($parentNames as $parentName) {

                if (strpos($parentName, '\\') !== false) {

                    $this->currentDefinition->extends[] = $parentName;

                } else {

                    $this->currentDefinition->extends[] = '\\' . $this->currentDefinition->namespace . '\\' . $parentName;
                }
            }

        } else {

            foreach ($this->currentDefinition->usedNamespaces as $alias) {

                foreach ($parentNames as $parentName) {

                    if (strpos($alias, $parentName) !== false) {

                        $this->currentDefinition->extends = '\\' . $alias;
                    }
                }
            }
        }

        // Clean possible double-\
        $this->currentDefinition->extends = str_replace('\\\\', '\\', $this->currentDefinition->extends);

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

        $this->currentDefinition->functionDefinitions = $functionParser->getDefinitionListFromTokens($tokens);

        return $this->currentDefinition;
    }
}
