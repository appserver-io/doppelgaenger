<?php

/**
 * \AppserverIo\Doppelgaenger\Parser\AbstractParser
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

use AppserverIo\Doppelgaenger\Config;
use AppserverIo\Doppelgaenger\Interfaces\ParserInterface;
use AppserverIo\Doppelgaenger\StructureMap;
use AppserverIo\Doppelgaenger\Entities\Definitions\StructureDefinitionHierarchy;
use AppserverIo\Doppelgaenger\Exceptions\ParserException;
use AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface;

/**
 * The abstract class AbstractParser which provides a basic implementation other parsers can inherit from
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
abstract class AbstractParser implements ParserInterface
{

    /**
     * The aspect of the configuration we need
     *
     * @var \AppserverIo\Doppelgaenger\Config $config
     */
    protected $config;

    /**
     * The path of the file we want to parse
     *
     * @var string $file
     */
    protected $file;

    /**
     * The token array representing the whole file
     *
     * @var array $tokens
     */
    protected $tokens = array();

    /**
     * The count of our main token array, so we do not have to calculate it over and over again
     *
     * @var integer $tokenCount
     */
    protected $tokenCount;

    /**
     * The current definition we are working on.
     * This should be filled during parsing and should be passed down to whatever parser we need so we know about
     * the current "parent" definition parts.
     *
     * @var \AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface $currentDefinition
     */
    protected $currentDefinition;

    /**
     * The list of structures (within this hierarchy) which we already parsed.
     *
     * @var \AppserverIo\Doppelgaenger\Entities\Definitions\StructureDefinitionHierarchy $structureDefinitionHierarchy
     */
    protected $structureDefinitionHierarchy;

    /**
     * Our structure map instance
     *
     * @var \AppserverIo\Doppelgaenger\StructureMap $structureMap
     */
    protected $structureMap;

    /**
     * Default constructor
     *
     * @param string                                       $file                         The path of the file we want to parse
     * @param \AppserverIo\Doppelgaenger\Config            $config                       Configuration
     * @param StructureDefinitionHierarchy                 $structureDefinitionHierarchy List of already parsed structures
     * @param \AppserverIo\Doppelgaenger\StructureMap|null $structureMap                 Our structure map instance
     * @param StructureDefinitionInterface|null            $currentDefinition            The current definition we are working on
     * @param array                                        $tokens                       The array of tokens taken from the file
     *
     * @throws \AppserverIo\Doppelgaenger\Exceptions\ParserException
     */
    public function __construct(
        $file,
        Config $config,
        StructureDefinitionHierarchy $structureDefinitionHierarchy = null,
        StructureMap $structureMap = null,
        StructureDefinitionInterface $currentDefinition = null,
        array $tokens = array()
    ) {
        $this->config = $config;

        if (empty($tokens)) {
            // Check if we can use the file
            if (!is_readable($file)) {
                throw new ParserException(sprintf('Could not read input file %s', $file));
            }

            // Get all the tokens and count them
            $this->tokens = token_get_all(file_get_contents($file));

        } else {
            $this->tokens = $tokens;
        }

        // We need the file saved
        $this->file = $file;

        // We also need the token count
        $this->tokenCount = count($this->tokens);

        $this->currentDefinition = $currentDefinition;

        $this->structureMap = is_null($structureMap) ? new StructureMap($config->getValue('autoloader/dirs'), $config->getValue('enforcement/dirs'), $config) : $structureMap;
        $this->structureDefinitionHierarchy = is_null($structureDefinitionHierarchy) ? new StructureDefinitionHierarchy() : $structureDefinitionHierarchy;
    }

    /**
     * Does a certain block of code contain a certain keyword
     *
     * @param string $docBlock The code block to search in
     * @param string $keyword  The keyword to search for
     *
     * @return boolean
     */
    protected function usesKeyword(
        $docBlock,
        $keyword
    ) {
        if (strpos($docBlock, $keyword) === false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get the starting line of the structure, FALSE if unknown
     *
     * @param array $tokens The token array
     *
     * @return integer|boolean
     */
    protected function getStartLine($tokens)
    {
        // Check the tokens
        $targetToken = $this->getToken();
        $tokenCount = count($tokens);
        for ($i = 0; $i < $tokenCount; $i++) {
            // If we got the target token indicating the structure start
            if ($tokens[$i][0] === $targetToken) {
                return $tokens[$i][2];
            }
        }

        // Return that we found nothing
        return false;
    }

    /**
     * Get the ending line of the structure, FALSE if unknown
     *
     * @param array $tokens The token array
     *
     * @return integer|boolean
     */
    protected function getEndLine($tokens)
    {
        // Check the tokens for a line number
        $lastIndex = (count($tokens) - 1);
        for ($i = $lastIndex; $i >= 0; $i--) {
            // If we got a token we know about the line number of the last token
            if (is_array($tokens[$i])) {
                // we found something already
                $endLine = $tokens[$i][2];
                // might be a linebreak as well
                if ($tokens[$i][0] === T_WHITESPACE) {
                    $endLine += substr_count($tokens[$i][1], "\n");
                }
                return $endLine;
            }
        }

        // Return that we found nothing
        return false;
    }

    /**
     * Will search for a certain token in a certain entity.
     *
     * This method will search the signature of either a class or a function for a certain token e.g. final.
     * Will return true if the token is found, and false if not or an error occurred.
     *
     * @param array   $tokens        The token array to search in
     * @param integer $searchedToken The token we search for, use PHP tokens here
     * @param integer $parsedEntity  The type of entity we search in front of, use PHP tokens here
     *
     * @return boolean
     */
    protected function hasSignatureToken(
        $tokens,
        $searchedToken,
        $parsedEntity
    ) {
        // We have to check what kind of structure we will check. Class and function are the only valid ones.
        if ($parsedEntity !== T_FUNCTION && $parsedEntity !== T_CLASS && $parsedEntity !== T_INTERFACE) {
            return false;
        }

        // Check the tokens
        for ($i = 0; $i < count($tokens); $i++) {
            // If we got the function name we have to check if we have the final keyword in front of it.
            // I would say should be within 6 tokens in front of the function keyword.
            if ($tokens[$i][0] === $parsedEntity) {
                // Check if our $i is lower than 6, if so we have to avoid getting into a negative range
                if ($i < 6) {
                    $i = 6;
                }

                for ($j = $i - 1; $j >= $i - 6; $j--) {
                    if ($tokens[$j][0] === $searchedToken) {
                        return true;
                    }
                }

                // We passed the 6 token loop but did not find something. So report it.
                return false;
            }
        }

        // We are still here? That should not be.
        return false;
    }

    /**
     * Will return the DocBlock of a certain entity.
     *
     * @param array   $tokens         The token array to search in
     * @param integer $structureToken The type of entity we search in front of, use PHP tokens here
     *
     * @return string
     */
    protected function getDocBlock(
        $tokens,
        $structureToken
    ) {
        // The general assumption is: if there is a doc block
        // before the class definition, and the class header follows after it within 6 tokens, then it
        // is the comment block for this class.
        $docBlock = '';
        $passedClass = false;
        for ($i = 0; $i < count($tokens); $i++) {
            // If we passed the class token
            if ($tokens[$i][0] === $structureToken) {
                $passedClass = true;
            }

            // If we got the docblock without passing the class before
            if ($tokens[$i][0] === T_DOC_COMMENT && $passedClass === false) {
                // Check if we are in front of a class definition
                for ($j = $i + 1; $j < $i + 8; $j++) {
                    if ($tokens[$j][0] === $structureToken) {
                        $docBlock = $tokens[$i][1];
                        break;
                    }
                }

                // Still here?
                break;
            }
        }

        // Return what we did or did not found
        return $docBlock;
    }
}
