<?php

/**
 * \AppserverIo\Doppelgaenger\Parser\FunctionParser
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

use AppserverIo\Doppelgaenger\Entities\Definitions\ParameterDefinition;
use AppserverIo\Doppelgaenger\Entities\Definitions\Structure;
use AppserverIo\Doppelgaenger\Entities\Joinpoint;
use AppserverIo\Doppelgaenger\Entities\Lists\FunctionDefinitionList;
use AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition;
use AppserverIo\Doppelgaenger\Entities\Lists\ParameterDefinitionList;
use AppserverIo\Doppelgaenger\Dictionaries\ReservedKeywords;
use AppserverIo\Psr\MetaobjectProtocol\Dbc\Annotations\Ensures;
use AppserverIo\Psr\MetaobjectProtocol\Dbc\Annotations\Requires;
use AppserverIo\Doppelgaenger\Utils\Parser;

/**
 * This class implements a parser to find all useful information in function definitions
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class FunctionParser extends AbstractParser
{

    /**
     * Token representing the structure this parser is used for
     *
     * @var integer TOKEN
     */
    const TOKEN = T_FUNCTION;

    /**
     * Will return the token representing the construct
     *
     * @return integer
     */
    public function getToken()
    {
        return self::TOKEN;
    }

    /**
     * Will return a list of function definition objects extracted from a given token array
     *
     * @param array   $tokens       The token array
     * @param boolean $getRecursive Do we have to get the ancestral contents as well?
     *
     * @return boolean|\AppserverIo\Doppelgaenger\Entities\Lists\FunctionDefinitionList
     */
    public function getDefinitionListFromTokens(array $tokens, $getRecursive = true)
    {
        // First of all we need to get the function tokens
        $tokens = $this->getFunctionTokens($tokens);

        // Did we get something valuable?
        $functionDefinitionList = new FunctionDefinitionList();
        $tokenCount = count($tokens);
        if ($tokens === false) {
            return false;

        } elseif ($tokenCount === 1) {
            // We got what we came for, or did we?
            if (isset($tokens[0])) {
                $functionDefinitionList->add($this->getDefinitionFromTokens($tokens[0], $getRecursive));
            }

            return $functionDefinitionList;

        } elseif ($tokenCount > 1) {
            // We are still here, but got a function name to look for
            foreach ($tokens as $token) {
                try {
                    $functionDefinitionList->add($this->getDefinitionFromTokens($token, $getRecursive));

                } catch (\UnexpectedValueException $e) {
                    // Just try the next one
                    continue;
                }
            }
        }

        return $functionDefinitionList;
    }

    /**
     * Will return a function definition objects for a certain function
     *
     * @param string  $functionName The name of the function to parse
     * @param boolean $getRecursive Do we have to get the ancestral contents as well?
     *
     * @return boolean|\AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition
     */
    public function getDefinition($functionName, $getRecursive = true)
    {
        // First of all we need to get the function tokens
        $tokens = $this->getFunctionTokens($this->tokens);
        $tokenCount = count($tokens);

        // Did we get something valuable?
        if ($tokens === false) {
            return false;

        } elseif ($tokenCount === 1) {
            // We got what we came for, or did we?
            if (isset($tokens[0])) {
                return $this->getDefinitionFromTokens($tokens[0], $getRecursive);
            }

        } elseif ($tokenCount > 1) {
            // We are still here, but got a function name to look for
            foreach ($tokens as $token) {
                // Now iterate over the array and search for the class we want
                for ($i = 0; $i < count($token); $i++) {
                    if (is_array($token[$i]) && $token[$i] === T_FUNCTION && $token[$i + 2] === $functionName) {
                        return $this->getDefinitionFromTokens($token, $getRecursive);
                    }
                }
            }
        }

        // Still here? That sounds bad
        return false;
    }

    /**
     * Returns a FunctionDefinition from a token array.
     *
     * This method will use a set of other methods to parse a token array and retrieve any
     * possible information from it. This information will be entered into a FunctionDefinition object.
     *
     * @param array   $tokens       The token array
     * @param boolean $getRecursive Do we have to get the ancestral conditions as well?
     *
     * @return \AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition
     */
    protected function getDefinitionFromTokens(array $tokens, $getRecursive)
    {
        // First of all we need a new FunctionDefinition to fill
        $functionDefinition = new FunctionDefinition();

        // For our next step we would like to get the doc comment (if any)
        $functionDefinition->setDocBlock($this->getDocBlock($tokens, T_FUNCTION));

        // Get start and end line
        $functionDefinition->setStartLine($this->getStartLine($tokens));
        $functionDefinition->setEndLine($this->getEndLine($tokens));

        // Get the function signature
        $functionDefinition->setIsFinal($this->hasSignatureToken($tokens, T_FINAL, T_FUNCTION));
        $functionDefinition->setIsAbstract($this->hasSignatureToken($tokens, T_ABSTRACT, T_FUNCTION));
        $functionDefinition->setVisibility($this->getFunctionVisibility($tokens));
        $functionDefinition->setIsStatic($this->hasSignatureToken($tokens, T_STATIC, T_FUNCTION));
        $functionDefinition->setName($this->getFunctionName($tokens));
        $functionDefinition->setStructureName($this->currentDefinition->getQualifiedName());

        // Lets also get out parameters
        $functionDefinition->setParameterDefinitions($this->getParameterDefinitionList($tokens));

        // Do we have a private context here? If so we have to tell the annotation parser
        $privateContext = false;
        if ($functionDefinition->getVisibility() === 'private') {
            $privateContext = true;
        }

        // So we got our docBlock, now we can parse the precondition annotations from it
        $annotationParser = new AnnotationParser($this->file, $this->config, $this->tokens, $this->currentDefinition);
        $functionDefinition->setPreconditions($annotationParser->getConditions(
            $functionDefinition->getDocBlock(),
            Requires::ANNOTATION,
            $privateContext
        ));

        // get the advices
        $functionDefinition->getPointcutExpressions()->attach($annotationParser->getPointcutExpressions(
            $functionDefinition->getDocBlock(),
            Joinpoint::TARGET_METHOD,
            $functionDefinition->getName()
        ));

        // Does this method require the use of our "old" mechanism?
        $functionDefinition->setUsesOld($this->usesKeyword($functionDefinition->getDocBlock(), ReservedKeywords::OLD));

        // We have to get the body of the function, so we can recreate it
        $functionDefinition->setBody($this->getFunctionBody($tokens));

        // So we got our docBlock, now we can parse the postcondition annotations from it
        $functionDefinition->setPostconditions($annotationParser->getConditions(
            $functionDefinition->getDocBlock(),
            Ensures::ANNOTATION,
            $privateContext
        ));

        // If we have to parse the definition in a recursive manner, we have to get the parent invariants
        if ($getRecursive === true) {
            // Add all the assertions we might get from ancestral dependencies
            $this->addAncestralAssertions($functionDefinition);
        }

        return $functionDefinition;
    }

    /**
     * This method will add all assertions any ancestral structures (parent classes, implemented interfaces) might have
     * to the passed class definition.
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition $functionDefinition The function definition
     *                                                                                      we are working on
     *
     * @return void
     */
    protected function addAncestralAssertions(FunctionDefinition $functionDefinition)
    {
        $dependencies = $this->currentDefinition->getDependencies();
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

            // Get the function definitions of the dependency structure
            $dependencyFunctionDefinitions = $dependencyDefinition->getFunctionDefinitions();

            // If we have a method with the name of the current one we have to get the conditions as ancestrals
            if ($dependencyFunctionDefinitions->entryExists($functionDefinition->getName())) {
                // Get the definition
                $dependencyFunctionDefinition = $dependencyFunctionDefinitions->get($functionDefinition->getName());
                // If the ancestral function uses the old keyword we have to do too
                if ($dependencyFunctionDefinition->usesOld() !== false) {
                    $functionDefinition->setUsesOld(true);
                }

                // Get the conditions
                $functionDefinition->setAncestralPreconditions($dependencyFunctionDefinition->getAllPreconditions(true));
                $functionDefinition->setAncestralPostconditions($dependencyFunctionDefinition->getAllPostconditions(true));
            }
        }
    }

    /**
     * Will return a list of parameter definition objects extracted from a given token array
     *
     * @param array $tokens The token array
     *
     * @return \AppserverIo\Doppelgaenger\Entities\Lists\ParameterDefinitionList
     *
     * TODO Does this have to be this long?
     */
    public function getParameterDefinitionList(
        array $tokens
    ) {
        // Check the tokens
        $parameterString = '';
        $parameterDefinitionList = new ParameterDefinitionList();
        $tokenCount = count($tokens);
        for ($i = 0; $i < $tokenCount; $i++) {
            // If we got the function definition, no scan everything from the first ( to the next )
            if ($tokens[$i][0] === T_FUNCTION) {
                $bracketPassed = null;
                for ($j = $i; $j < $tokenCount; $j++) {
                    // If we got the function definition, no scan everything from the first ( to the closing )
                    if ($tokens[$j] === '(') {
                        if ($bracketPassed === null) {
                            $bracketPassed = 0;
                            // We do not want to get this token as well.
                            continue;

                        } else {
                            $bracketPassed++;
                        }
                    }

                    // We got A closing bracket, decrease the counter
                    if ($tokens[$j] === ')') {
                        $bracketPassed--;
                    }

                    if ($bracketPassed >= 0 && $bracketPassed !== null) {
                        // Collect what we get
                        if (is_array($tokens[$j])) {
                            $parameterString .= $tokens[$j][1];

                        } else {
                            $parameterString .= $tokens[$j];
                        }
                    } elseif ($bracketPassed !== null) {
                        // If we got the closing bracket we can leave both loops
                        break 2;
                    }
                }
            }
        }

        // Now lets analyse what we got
        $parameterStrings = explode(',', $parameterString);
        $parserUtils = new Parser();
        foreach ($parameterStrings as $key => $param) {
            if ($parserUtils->getBracketCount($param, '(') > 0) {
                $param = $param . ', ' . $parameterStrings[$key + 1];
                unset($parameterStrings[$key + 1]);
            }

            $param = trim($param);
            $paramPieces = explode('$', $param);

            // Get a new ParameterDefinition
            $parameterDefinition = new ParameterDefinition();

            // we either get one or two pieces
            if (count($paramPieces) === 1) {
                continue;

            } elseif (count($paramPieces) === 2) {
                $parameterDefinition->type = trim($paramPieces[0]);

                // We might have an overload going on
                $nameArray = explode('=', $paramPieces[1]);
                $parameterDefinition->name = '$' . trim($nameArray[0]);

                // check if we got a default value for overloading
                if (isset($nameArray[1])) {
                    unset($nameArray[0]);
                    $parameterDefinition->defaultValue = trim(implode('=', $nameArray));
                }
            }

            // Add the definition to the list
            $parameterDefinitionList->add($parameterDefinition);
        }

        return $parameterDefinitionList;
    }

    /**
     * Will return the name of the function passed as a token array
     *
     * @param array $tokens The token array
     *
     * @return string
     */
    public function getFunctionName(
        array $tokens
    ) {
        // Check the tokens
        $functionName = '';
        for ($i = 0; $i < count($tokens); $i++) {
            // If we got the function name
            if ($tokens[$i][0] === T_FUNCTION && $tokens[$i + 2][0] === T_STRING) {
                $functionName = $tokens[$i + 2][1];
            }
        }

        // Return what we did or did not found
        return $functionName;
    }

    /**
     * Will return the body of the function passed as a token array
     *
     * @param array $tokens The token array
     *
     * @return string
     */
    public function getFunctionBody(
        array $tokens
    ) {
        // We will iterate over the token array and collect everything
        // from the first opening curly bracket until the last
        $functionBody = '';
        for ($i = 0; $i < count($tokens); $i++) {
            // If we passed the function token
            if ($tokens[$i][0] === T_FUNCTION) {
                $passedFunction = true;
            }

            // If we got the curly bracket that opens the function
            if ($tokens[$i] === '{' && $passedFunction === true) {
                // Get everything until we reach the closing bracket
                $bracketCounter = 1;
                for ($j = $i + 1; $j < count($tokens); $j++) {
                    // We have to count brackets. When they are even again we will break.
                    if ($tokens[$j] === '{' || $tokens[$j][0] === T_CURLY_OPEN) {
                        $bracketCounter++;

                    } elseif ($tokens[$j] === '}') {
                        $bracketCounter--;
                    }

                    // Do we have an even amount of brackets yet?
                    if ($bracketCounter === 0) {
                        return $functionBody;
                    }

                    // Collect what we get
                    if (is_array($tokens[$j])) {
                        $functionBody .= $tokens[$j][1];

                    } else {
                        $functionBody .= $tokens[$j];
                    }
                }
            }
        }

        // Return what we did or did not found
        return $functionBody;
    }

    /**
     * Will extract tokens belonging to one function (and one function only)
     *
     * @param array $tokens The token array
     *
     * @return array|boolean
     */
    protected function getFunctionTokens(
        array $tokens
    ) {
        // Iterate over all the tokens and filter the different function portions out
        $result = array();
        for ($i = 0; $i < count($tokens); $i++) {
            // If we got a function keyword, we have to check how far the function extends,
            // then copy the array within that bounds, but first of all we have to check if we got
            // a function name.
            // Otherwise anonymous functions will make us go crazy.
            if (is_array($tokens[$i]) && $tokens[$i][0] === T_FUNCTION &&
                !empty($tokens[$i + 2]) && $tokens[$i + 2] !== '('
            ) {
                // The lower bound should be the last semicolon|closing curly bracket|PHP tag before the function
                $lowerBound = 0;
                for ($j = $i - 1; $j >= 0; $j--) {
                    if ($tokens[$j] === ';' || $tokens[$j] === '{' ||
                        (is_array($tokens[$j]) && $tokens[$j][0] === T_OPEN_TAG) ||
                        $tokens[$j][0] === T_CURLY_OPEN
                    ) {
                        $lowerBound = $j + 1;
                        break;
                    }
                }

                // The upper bound should be the first time the curly brackets are even again or the first occurrence
                // of the semicolon. The semicolon is important, as we have to get function declarations in interfaces
                // as well.
                $upperBound = count($tokens) - 1;
                $bracketCounter = null;
                for ($j = $i + 1; $j < count($tokens); $j++) {
                    if ($tokens[$j] === '{' || $tokens[$j][0] === T_CURLY_OPEN) {
                        // If we still got null set to 0
                        if ($bracketCounter === null) {
                            $bracketCounter = 0;
                        }

                        $bracketCounter++;

                    } elseif ($tokens[$j] === '}') {
                        // If we still got null set to 0
                        if ($bracketCounter === null) {
                            $bracketCounter = 0;
                        }

                        $bracketCounter--;
                    }

                    // Did we reach a semicolon before reaching a opening curly bracket?
                    if ($bracketCounter === null && $tokens[$j] === ';') {
                        $upperBound = $j + 1;
                        break;
                    }

                    // Do we have an even amount of brackets yet?
                    if ($bracketCounter === 0) {
                        $upperBound = $j + 1;
                        break;
                    }
                }

                $result[] = array_slice($tokens, $lowerBound, $upperBound - $lowerBound);
            }
        }

        return $result;
    }

    /**
     * Will return the visibility of the function passed as a token array
     *
     * @param array $tokens The token array
     *
     * @return string
     *
     * TODO I am sure this can be done more generally usable
     */
    public function getFunctionVisibility(
        array $tokens
    ) {
        // Check out all the tokens and look if we find the right thing. We can do that as these keywords are not valid
        // within a function definition. Public is default.
        $visibility = 'public';
        for ($i = 0; $i < count($tokens); $i++) {
            // Search for the visibility
            if (is_array($tokens[$i]) && ($tokens[$i][0] === T_PRIVATE || $tokens[$i][0] === T_PROTECTED)) {
                // Got it!
                $visibility = $tokens[$i][1];
            }

            // Did we reach the function already?
            if ($tokens[$i][0] === T_FUNCTION) {
                break;
            }
        }

        return $visibility;
    }
}
