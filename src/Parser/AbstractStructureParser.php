<?php
/**
 * File containing the AbstractStructureParser class
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

use AppserverIo\Doppelgaenger\Entities\Lists\StructureDefinitionList;
use AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface;
use AppserverIo\Doppelgaenger\Interfaces\StructureParserInterface;
use AppserverIo\Doppelgaenger\Entities\Definitions\FileDefinition;

/**
 * AppserverIo\Doppelgaenger\Parser\AbstractStructureParser
 *
 * The abstract class AbstractStructureParser which provides a basic implementation other stucture parsers
 * can inherit from
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Parser
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
abstract class AbstractStructureParser extends AbstractParser implements StructureParserInterface
{

    /**
     * Will return the constants within the main token array
     *
     * @return array
     */
    public function getConstants()
    {
        // Check the tokens
        $constants = array();
        for ($i = 0; $i < $this->tokenCount; $i++) {

            // If we got the class name
            if ($this->tokens[$i][0] === T_CONST) {

                for ($j = $i + 1; $j < $this->tokenCount; $j++) {

                    if ($this->tokens[$j] === ';') {

                        break;

                    } elseif ($this->tokens[$j][0] === T_STRING) {

                        $constants[$this->tokens[$j][1]] = '';

                        for ($k = $j + 1; $k < count($this->tokens); $k++) {

                            if ($this->tokens[$k] === ';') {

                                break;

                            } elseif (is_array($this->tokens[$k]) && $this->tokens[$k][0] !== '=') {

                                $constants[$this->tokens[$j][1]] .= $this->tokens[$k][1];
                            }
                        }

                        // Now trim what we got
                        $constants[$this->tokens[$j][1]] = trim($constants[$this->tokens[$j][1]]);
                    }
                }
            }
        }

        // Return what we did or did not found
        return $constants;
    }

    /**
     * Will return the definition of a specified structure
     *
     * @param null|string $name         The name of the class we are searching for
     * @param bool        $getRecursive Do we have to get the ancestral conditions as well?
     *
     * @return bool|\AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface
     */
    public function getDefinition($name = null, $getRecursive = true)
    {
        // Maybe we already got this structure?
        if ($this->structureDefinitionHierarchy->entryExists($name)) {

            return $this->structureDefinitionHierarchy->getEntry($name);
        }

        // First of all we need to get the class tokens
        $tokens = $this->getStructureTokens($this->getToken());

        // Did we get something valuable?
        if ($tokens === false) {

            return false;

        } elseif ($name === null && count($tokens) > 1) {
            // If we did not get a class name and we got more than one class we can fail right here
            return false;

        } elseif (count($tokens) === 1) {

            // We got what we came for
            return $this->getDefinitionFromTokens($tokens[0], $getRecursive);

        } elseif (is_string($name) && count($tokens) > 1) {
            // We are still here, but got a class name to look for

            foreach ($tokens as $key => $token) {

                // Now iterate over the array and search for the class we want
                for ($i = 0; $i < count($token); $i++) {

                    if (is_array($token[$i]) && $token[$i] === $this->getToken() && $token[$i + 2] === $name) {

                        return $this->getDefinitionFromTokens($tokens[$key], $getRecursive);
                    }
                }
            }
        }

        // Still here? Must be an error.
        return false;
    }

    /**
     * Will return a list of found structures or false on error
     *
     * @param string         $file           Path of the file we are searching in
     * @param FileDefinition $fileDefinition Definition of the file the class is in
     * @param bool           $getRecursive   Do we have to get the ancestral conditions as well?
     *
     * @return bool|StructureDefinitionList
     */
    public function getDefinitionListFromFile($file, FileDefinition $fileDefinition, $getRecursive = true)
    {
        // Get all the token arrays for the different classes
        $tokens = $this->getStructureTokens($file, $this->getToken());

        // Did we get the right thing?
        if (!is_array($tokens)) {

            return false;
        }

        $structureDefinitionList = new StructureDefinitionList();
        foreach ($tokens as $token) {

            try {

                $structureDefinitionList->add($this->getDefinitionFromTokens($token, $fileDefinition, $getRecursive));

            } catch (\UnexpectedValueException $e) {
                // Just try the next one

                continue;
            }
        }

        return $structureDefinitionList;
    }

    /**
     * Get the name of the structure
     *
     * @param array $tokens The token array
     *
     * @return string
     */
    protected function getName($tokens)
    {
        // Check the tokens
        $name = '';
        $targetToken = $this->getToken();
        for ($i = 0; $i < count($tokens); $i++) {

            // If we got the class name
            if ($tokens[$i][0] === $targetToken) {

                for ($j = $i + 1; $j < count($tokens); $j++) {

                    if ($tokens[$j] === '{') {

                        $name = $tokens[$i + 2][1];
                    }
                }
            }
        }

        // Return what we did or did not found
        return $name;
    }

    /**
     * Will return the structure's namespace if found
     *
     * @return string
     */
    public function getNamespace()
    {
        // Check the tokens
        $namespace = '';
        for ($i = 0; $i < $this->tokenCount; $i++) {

            // If we got the namespace
            if ($this->tokens[$i][0] === T_NAMESPACE) {

                for ($j = $i + 1; $j < count($this->tokens); $j++) {

                    if ($this->tokens[$j][0] === T_STRING) {

                        $namespace .= '\\' . $this->tokens[$j][1];

                    } elseif ($this->tokens[$j] === '{' ||
                        $this->tokens[$j] === ';' ||
                        $this->tokens[$j][0] === T_CURLY_OPEN
                    ) {

                        break;
                    }
                }
            }
        }

        // Return what we did or did not found
        return substr($namespace, 1);
    }

    /**
     * Will check the main token array for the occurrence of a certain on (class, interface or trait)
     *
     * @return string|boolean
     */
    protected function getStructureToken()
    {
        for ($i = 0; $i < $this->tokenCount; $i++) {

            switch ($this->tokens[$i][0]) {

                case T_CLASS:

                    return 'class';
                    break;

                case T_INTERFACE:

                    return 'interface';
                    break;

                case T_TRAIT:

                    return 'trait';
                    break;

                default:

                    continue;
                    break;
            }
        }

        // We are still here? That should not be.
        return false;
    }

    /**
     * Will return a subset of our main token array. This subset includes all tokens belonging to a certain structure.
     * Might return false on failure
     *
     * @param integer $structureToken The structure we are after e.g. T_CLASS, use PHP tokens here
     *
     * @return array|boolean
     */
    protected function getStructureTokens($structureToken)
    {
        // Now iterate over the array and filter different classes from it
        $result = array();
        for ($i = 0; $i < $this->tokenCount; $i++) {

            // If we got a class keyword, we have to check how far the class extends,
            // then copy the array withing that bounds
            if (is_array($this->tokens[$i]) && $this->tokens[$i][0] === $structureToken) {

                // The lower bound should be the last semicolon|closing curly bracket|PHP tag before the class
                $lowerBound = 0;
                for ($j = $i - 1; $j >= 0; $j--) {

                    if ($this->tokens[$j] === ';' || $this->tokens[$j] === '}' ||
                        is_array($this->tokens[$j]) && $this->tokens[$j][0] === T_OPEN_TAG
                    ) {

                        $lowerBound = $j;
                        break;
                    }
                }

                // The upper bound should be the first time the curly brackets are even again
                $upperBound = $this->tokenCount - 1;
                $bracketCounter = null;
                for ($j = $i + 1; $j < count($this->tokens); $j++) {

                    if ($this->tokens[$j] === '{' || $this->tokens[$j][0] === T_CURLY_OPEN) {

                        // If we still got null set to 0
                        if ($bracketCounter === null) {

                            $bracketCounter = 0;
                        }

                        $bracketCounter++;

                    } elseif ($this->tokens[$j] === '}') {

                        // If we still got null set to 0
                        if ($bracketCounter === null) {

                            $bracketCounter = 0;
                        }

                        $bracketCounter--;
                    }

                    // Do we have an even amount of brackets yet?
                    if ($bracketCounter === 0) {

                        $upperBound = $j;
                        break;
                    }
                }

                $result[] = array_slice($this->tokens, $lowerBound, $upperBound - $lowerBound);
            }
        }

        // Last line of defence; did we get something?
        if (empty($result)) {

            return false;
        }

        return $result;
    }

    /**
     * Will return the token representing the structure the parser is used for e.g. T_CLASS
     *
     * @return integer
     */
    public function getToken()
    {
        return static::TOKEN;
    }

    /**
     * Will return an array of structures which this structure references by use statements
     *
     * @return array
     *
     * TODO namespaces does not make any sense here, as we are referencing structures!
     */
    public function getUsedNamespaces()
    {
        // Check the tokens
        $namespaces = array();
        for ($i = 0; $i < $this->tokenCount; $i++) {

            // If we got a use statement
            if ($this->tokens[$i][0] === T_USE) {

                $namespace = '';
                for ($j = $i + 1; $j < count($this->tokens); $j++) {

                    if ($this->tokens[$j][0] === T_STRING) {

                        $namespace .= '\\' . $this->tokens[$j][1];

                    } elseif ($this->tokens[$j] === '{' ||
                        $this->tokens[$j] === ';' ||
                        $this->tokens[$j][0] === T_CURLY_OPEN
                    ) {

                        $namespaces[] = $namespace;
                        break;
                    }
                }
            }
        }

        // Return what we did or did not found
        return $namespaces;
    }

    /**
     * Will check if a certain structure was mentioned in one(!) use statement.
     *
     * @param StructureDefinitionInterface $structureDefinition The structure $structureName is compared against
     * @param string                       $structureName       The name of the structure we have to check against the
     *                                                          use statements of the definition
     *
     * @return bool|string
     */
    protected function resolveUsedNamespace(StructureDefinitionInterface & $structureDefinition, $structureName)
    {
        // If there was no useful name passed we can fail right here
        if (empty($structureName)) {

            return false;
        }

        // Walk over all namespaces and if we find something we will act accordingly.
        $result = $structureDefinition->getQualifiedName();
        foreach ($structureDefinition->getUsedNamespaces() as $key => $usedNamespace) {

            // Check if the last part of the use statement is our structure
            $tmp = explode('\\', $usedNamespace);
            if (array_pop($tmp) === $structureName) {

                // Tell them we succeeded
                return trim(implode('\\', $tmp) . '\\' . $structureName, '\\');
            }
        }

        // We did not seem to have found anything. Might it be that we are in our own namespace?
        if ($structureDefinition->getNamespace() !== null && strpos($structureName, '\\') !== 0) {

            return $structureDefinition->getNamespace() . '\\' . $structureName;
        }

        // Still here? Return what we got.
        return $result;
    }
}
