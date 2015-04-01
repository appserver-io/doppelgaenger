<?php

/**
 * \AppserverIo\Doppelgaenger\Parser\InterfaceParser
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

use AppserverIo\Doppelgaenger\Entities\Definitions\InterfaceDefinition;
use AppserverIo\Doppelgaenger\Entities\Definitions\FileDefinition;
use AppserverIo\Doppelgaenger\Entities\Lists\StructureDefinitionList;
use AppserverIo\Doppelgaenger\Exceptions\GeneratorException;
use AppserverIo\Psr\MetaobjectProtocol\Dbc\Annotations\Invariant;

/**
 * The InterfaceParser class which is used to get an \AppserverIo\Doppelgaenger\Entities\Definitions\InterfaceDefinition
 * instance (or several) from a fail containing those definition(s)
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
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
     * @return array
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
        foreach ($parents as $key => $parent) {
            $parents[$key] = trim($parent);

            // We do not want empty stuff
            if (empty($parents[$key])) {
                unset($parents[$key]);
            }
        }

        return $parents;
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
        $this->currentDefinition->setPath($this->file);

        // Get the interfaces own namespace and the namespace which are included via use
        $this->currentDefinition->setNamespace($this->getNamespace());
        $this->currentDefinition->setUsedStructures($this->getUsedStructures());

        // For our next step we would like to get the doc comment (if any)
        $this->currentDefinition->setDocBlock($this->getDocBlock($tokens, T_INTERFACE));

        // Get the interface identity
        $this->currentDefinition->setName($this->getName($tokens));

        // So we got our docBlock, now we can parse the invariant annotations from it
        $annotationParser = new AnnotationParser($this->file, $this->config, $this->tokens);
        $this->currentDefinition->setInvariantConditions($annotationParser->getConditions(
            $this->currentDefinition->getDocBlock(),
            Invariant::ANNOTATION
        ));

        // Lets check if there is any inheritance, or if we implement any interfaces
        $parentNames = $this->getParents($tokens);
        if (count($this->currentDefinition->getUsedStructures()) === 0) {
            foreach ($parentNames as $parentName) {
                if (strpos($parentName, '\\') !== false) {
                    $this->currentDefinition->getExtends()[] = $parentName;

                } else {
                    $this->currentDefinition->getExtends()[] = '\\' . $this->currentDefinition->getNamespace() . '\\' . $parentName;
                }
            }

        } else {
            foreach ($this->currentDefinition->getUsedStructures() as $alias) {
                foreach ($parentNames as $parentName) {
                    if (strpos($alias, $parentName) !== false) {
                        $this->currentDefinition->setExtends('\\' . $alias);
                    }
                }
            }
        }

        // Clean possible double-\
        $this->currentDefinition->setExtends(str_replace('\\\\', '\\', $this->currentDefinition->getExtends()));

        $this->currentDefinition->setConstants($this->getConstants($tokens));

        // Only thing still missing are the methods, so ramp up our FunctionParser
        $functionParser = new FunctionParser(
            $this->file,
            $this->config,
            $this->structureDefinitionHierarchy,
            $this->structureMap,
            $this->currentDefinition,
            $this->tokens
        );

        $this->currentDefinition->setFunctionDefinitions($functionParser->getDefinitionListFromTokens($tokens));

        return $this->currentDefinition;
    }
}
