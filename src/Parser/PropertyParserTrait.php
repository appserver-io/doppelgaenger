<?php
/**
 * File containing the PropertyParserTrait trait
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

use AppserverIo\Doppelgaenger\Entities\Definitions\AttributeDefinition;
use AppserverIo\Doppelgaenger\Entities\Lists\AttributeDefinitionList;
use AppserverIo\Doppelgaenger\Entities\Lists\TypedListList;

/**
 * AppserverIo\Doppelgaenger\Parser\PropertyParserTrait
 *
 * Trait which will allow the re-usability of methods for parsing structure properties
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Parser
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
trait PropertyParserTrait
{
    /**
     * Retrieves class attributes from token array.
     *
     * This method will search for any attributes a class might have. Just pass the token array of the class.
     * Work is done using token definitions and common sense in regards to PHP syntax.
     * To retrieve the different properties of an attribute it relies on getAttributeProperties().
     * We need the list of invariants to mark attributes wo are under surveillance.
     *
     * @param array         $tokens     Array of tokens for this class
     * @param TypedListList $invariants List of invariants so we can compare the attributes to
     *
     * @return AttributeDefinitionList
     */
    protected function getAttributes(array $tokens, TypedListList $invariants = null)
    {
        // Check the tokens
        $attributes = new AttributeDefinitionList();
        for ($i = 0; $i < count($tokens); $i++) {

            // If we got a variable we will check if there is any function definition above it.
            // If not, we got an attribute, if so we will check if there is an even number of closing and opening
            // brackets above it, which would mean we are not in the function.
            if (is_array($tokens[$i]) && $tokens[$i][0] === T_VARIABLE) {

                for ($j = $i - 1; $j >= 0; $j--) {

                    if (is_array($tokens[$j]) && $tokens[$j][0] === T_FUNCTION) {

                        // Initialize our counter and also the check if we even started counting
                        $bracketCounter = 0;
                        $usedCounter = false;

                        // We got something, lets count the brackets between it and our variable's position
                        for ($k = $j + 1; $k < $i; $k++) {

                            if ($tokens[$k] === '{' || $tokens[$k][0] === T_CURLY_OPEN) {

                                $usedCounter = true;
                                $bracketCounter++;

                            } elseif ($tokens[$k] === '}') {

                                $usedCounter = true;
                                $bracketCounter--;
                            }
                        }

                        // If we got an even number of brackets (the counter is 0 and got used), we got an attribute
                        if ($bracketCounter === 0 && $usedCounter === true) {

                            $attributes->set($tokens[$i][1], $this->getAttributeProperties($tokens, $i));
                        }

                        break;

                    } elseif (is_array($tokens[$j]) && $tokens[$j][0] === $this->getToken()) {
                        // If we reach the class definition without passing a function we definitely got an attribute

                        $attributes->set($tokens[$i][1], $this->getAttributeProperties($tokens, $i));
                        break;
                    }
                }
            }
        }

        // If we got invariants we will check if our attributes are used in invariants
        if ($invariants !== null) {

            // Lets iterate over all the attributes and check them against the invariants we got
            $listIterator = $invariants->getIterator();
            $listCount = $listIterator->count();
            $attributeIterator = $attributes->getIterator();
            $attributeCount = $attributeIterator->count();
            for ($i = 0; $i < $attributeCount; $i++) {

                // Do we have any of these attributes in our invariants?
                $listIterator = $invariants->getIterator();
                for ($j = 0; $j < $listCount; $j++) {

                    // Did we get anything useful?
                    if ($listIterator->current() === null) {

                        continue;
                    }
                    $invariantIterator = $listIterator->current()->getIterator();
                    $invariantCount = $invariantIterator->count();
                    for ($k = 0; $k < $invariantCount; $k++) {

                        $attributePosition = strpos(
                            $invariantIterator->current()->getString(),
                            '$this->' . ltrim(
                                $attributeIterator->current()->name,
                                '$'
                            )
                        );

                        if ($attributePosition !== false
                        ) {

                            // Tell them we were mentioned and persist it
                            $attributeIterator->current()->inInvariant = true;
                        }

                        $invariantIterator->next();
                    }
                    $listIterator->next();
                }
                $attributeIterator->next();
            }
        }

        return $attributes;
    }

    /**
     * Will return a definition of an attribute as far as we can extract it from the token array
     *
     * @param array $tokens            Array of tokens for this class
     * @param int   $attributePosition Position of the attribute within the token array
     *
     * @return AttributeDefinition
     */
    protected function getAttributeProperties(array $tokens, $attributePosition)
    {
        // We got the tokens and the position of the attribute, so look in front of it for visibility and a
        // possible static keyword
        $attribute = new AttributeDefinition();
        $attribute->name = $tokens[$attributePosition][1];
        $attribute->structureName = $this->currentDefinition->getQualifiedName();

        for ($i = $attributePosition; $i > $attributePosition - 6; $i--) {

            // Search for the visibility
            if (is_array($tokens[$i]) && ($tokens[$i][0] === T_PRIVATE || $tokens[$i][0] === T_PROTECTED)) {

                // Got it!
                $attribute->visibility = $tokens[$i][1];
            }

            // Do we get a static keyword?
            if (is_array($tokens[$i]) && $tokens[$i][0] === T_STATIC) {

                // default is false, so set it to true
                $attribute->isStatic = true;
            }
        }

        // Now check if there is any default value for this attribute, if so we have to get it
        $defaultValue = null;
        for ($i = $attributePosition; $i < count($tokens); $i++) {

            // If we reach the semicolon we do not have anything here.
            if ($tokens[$i] === ';') {

                break;
            }

            if ($defaultValue !== null) {
                // Do we get a static keyword?
                if (is_array($tokens[$i])) {

                    $defaultValue .= $tokens[$i][1];

                } else {

                    $defaultValue .= $tokens[$i];
                }
            }

            // If we pass a = we have to get ready to make notes
            if ($tokens[$i] === '=') {

                $defaultValue = '';
            }
        }

        // Set the default Value
        $attribute->defaultValue = $defaultValue;

        // Last but not least we have to check if got the visibility, if not, set it public.
        // This is necessary, as missing visibility in the definition will also default to public
        if ($attribute->visibility === '') {

            $attribute->visibility = 'public';
        }

        return $attribute;
    }

    /**
     * We by default assume we are used to parse classes
     *
     * @return int
     */
    public function getToken()
    {
        return T_CLASS;
    }
}