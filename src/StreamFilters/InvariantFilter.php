<?php

/**
 * \AppserverIo\Doppelgaenger\StreamFilters\InvariantFilter
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

namespace AppserverIo\Doppelgaenger\StreamFilters;

use AppserverIo\Doppelgaenger\Entities\Lists\AttributeDefinitionList;
use AppserverIo\Doppelgaenger\Entities\Lists\TypedListList;
use AppserverIo\Doppelgaenger\Exceptions\GeneratorException;
use AppserverIo\Doppelgaenger\Dictionaries\Placeholders;
use AppserverIo\Doppelgaenger\Dictionaries\ReservedKeywords;

/**
 * This filter will buffer the input stream and add all invariant related information at prepared locations
 * (see $dependencies)
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class InvariantFilter extends AbstractFilter
{

    /**
     * @const integer FILTER_ORDER Order number if filters are used as a stack, higher means below others
     */
    const FILTER_ORDER = 3;

    /**
     * @var array $dependencies Other filters on which we depend
     */
    protected $dependencies = array('SkeletonFilter');

    /**
     * The main filter method.
     * Implemented according to \php_user_filter class. Will loop over all stream buckets, buffer them and perform
     * the needed actions.
     *
     * @param resource $in       Incoming bucket brigade we need to filter
     * @param resource $out      Outgoing bucket brigade with already filtered content
     * @param integer  $consumed The count of altered characters as buckets pass the filter
     * @param boolean  $closing  Is the stream about to close?
     *
     * @throws \AppserverIo\Doppelgaenger\Exceptions\GeneratorException
     *
     * @return integer
     *
     * @link http://www.php.net/manual/en/php-user-filter.filter.php
     */
    public function filter($in, $out, &$consumed, $closing)
    {
        $structureDefinition = $this->params;

        // After iterate over the attributes and build up our array of attributes we have to include in our
        // checking mechanism.
        $obsoleteProperties = array();
        $propertyReplacements = array();
        $iterator = $structureDefinition->getAttributeDefinitions()->getIterator();
        for ($i = 0; $i < $iterator->count(); $i++) {
            // Get the current attribute for more easy access
            $attribute = $iterator->current();

            // Only enter the attribute if it is used in an invariant and it is not private
            if ($attribute->inInvariant() && $attribute->getVisibility() !== 'private') {
                // Build up our regex expression to filter them out
                $obsoleteProperties[] = '/' . $attribute->getVisibility() . '.*?\\' . $attribute->getName() . '/';
                $propertyReplacements[] = 'private ' . $attribute->getName();
            }

            // Move the iterator
            $iterator->next();
        }

        // Get our buckets from the stream
        $functionHook = '';
        while ($bucket = stream_bucket_make_writeable($in)) {
            // We only have to do that once!
            if (empty($functionHook)) {
                $functionHook = Placeholders::FUNCTION_HOOK . Placeholders::PLACEHOLDER_CLOSE;

                // Get the code for our attribute storage
                $attributeCode = $this->generateAttributeCode($structureDefinition->getAttributeDefinitions());

                // Get the code for the assertions
                $code = $this->generateFunctionCode($structureDefinition->getInvariants());

                // Insert the code
                $bucket->data = str_replace(
                    array(
                        $functionHook,
                        $functionHook
                    ),
                    array(
                        $functionHook . $attributeCode,
                        $functionHook . $code
                    ),
                    $bucket->data
                );

                // Determine if we need the __set method to be injected
                if ($structureDefinition->getFunctionDefinitions()->entryExists('__set')) {
                    // Get the code for our __set() method
                    $setCode = $this->generateSetCode($structureDefinition->hasParents(), true);
                    $bucket->data = str_replace(
                        Placeholders::METHOD_INJECT . '__set' . Placeholders::PLACEHOLDER_CLOSE,
                        $setCode,
                        $bucket->data
                    );

                } else {
                    $setCode = $this->generateSetCode($structureDefinition->hasParents());
                    $bucket->data = str_replace(
                        $functionHook,
                        $functionHook . $setCode,
                        $bucket->data
                    );
                }

                // Determine if we need the __get method to be injected
                if ($structureDefinition->getFunctionDefinitions()->entryExists('__get')) {
                    // Get the code for our __set() method
                    $getCode = $this->generateGetCode($structureDefinition->hasParents(), true);
                    $bucket->data = str_replace(
                        Placeholders::METHOD_INJECT . '__get' . Placeholders::PLACEHOLDER_CLOSE,
                        $getCode,
                        $bucket->data
                    );

                } else {
                    $getCode = $this->generateGetCode($structureDefinition->hasParents());
                    $bucket->data = str_replace(
                        $functionHook,
                        $functionHook . $getCode,
                        $bucket->data
                    );
                }
            }

            // We need the code to call the invariant
            $this->injectInvariantCall($bucket->data);

            // Remove all the properties we will take care of with our magic setter and getter
            $bucket->data = preg_replace($obsoleteProperties, $propertyReplacements, $bucket->data, 1);

            // Tell them how much we already processed, and stuff it back into the output
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }

        return PSFS_PASS_ON;
    }

    /**
     * Will generate the code needed to for managing the attributes in regards to invariants related to them
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Lists\AttributeDefinitionList $attributeDefinitions Defined attributes
     *
     * @return string
     */
    protected function generateAttributeCode(AttributeDefinitionList $attributeDefinitions)
    {
        // We should create attributes to store our attribute types
        $code = '/**
            * @var array
            */
            private $' . ReservedKeywords::ATTRIBUTE_STORAGE . ' = array(';

        // After iterate over the attributes and build up our array
        $iterator = $attributeDefinitions->getIterator();
        for ($i = 0; $i < $iterator->count(); $i++) {
            // Get the current attribute for more easy access
            $attribute = $iterator->current();

            // Only enter the attribute if it is used in an invariant and it is not private
            if ($attribute->inInvariant() && $attribute->getVisibility() !== 'private') {
                $code .= '"' . substr($attribute->getName(), 1) . '"';
                $code .= ' => array("visibility" => "' . $attribute->getVisibility() . '", ';
                // Now check if we need any keywords for the variable identity
                if ($attribute->isStatic()) {
                    $code .= '"static" => true';
                } else {
                    $code .= '"static" => false';
                }
                $code .= '),';
            }

            // Move the iterator
            $iterator->next();
        }
        $code .= ');
        ';

        return $code;
    }

    /**
     * Will generate the code of the magic __set() method needed to check invariants related to member variables
     *
     * @param boolean $hasParents Does this structure have parents
     * @param boolean $injected   Will the created method be injected or is it a stand alone method?
     *
     * @return string
     */
    protected function generateSetCode($hasParents, $injected = false)
    {

        // We only need the method header if we don't inject
        if ($injected === false) {
            $code = '/**
             * Magic function to forward writing property access calls if within visibility boundaries.
             *
             * @throws \Exception
             */
            public function __set($name, $value)
            {';
        } else {
            $code = '';
        }

        $code .= ReservedKeywords::CONTRACT_CONTEXT . ' = \AppserverIo\Doppelgaenger\ContractContext::open();
            ' . ReservedKeywords::FAILURE_VARIABLE . ' = array();
            ' . ReservedKeywords::UNWRAPPED_FAILURE_VARIABLE . ' = array();
            // Does this property even exist? If not, throw an exception
            if (!isset($this->' . ReservedKeywords::ATTRIBUTE_STORAGE . '[$name])) {';

        if ($hasParents) {
            $code .= 'return parent::__set($name, $value);';
        } else {
            $code .= 'if (property_exists($this, $name)) {' .

                ReservedKeywords::FAILURE_VARIABLE . '[] = "accessing $name in an invalid way";' .
                Placeholders::ENFORCEMENT . 'InvalidArgumentException' . Placeholders::PLACEHOLDER_CLOSE .
                '\AppserverIo\Doppelgaenger\ContractContext::close();
                return false;
                } else {' .

                ReservedKeywords::FAILURE_VARIABLE . '[] = "accessing $name as it does not exist";' .
                Placeholders::ENFORCEMENT . 'MissingPropertyException' . Placeholders::PLACEHOLDER_CLOSE .
                '\AppserverIo\Doppelgaenger\ContractContext::close();
                return false;
                }';
        }

        $code .= '}
        // Check if the invariant holds
            ' . Placeholders::INVARIANT . Placeholders::PLACEHOLDER_CLOSE .
            '// Now check what kind of visibility we would have
            $attribute = $this->' . ReservedKeywords::ATTRIBUTE_STORAGE . '[$name];
            switch ($attribute["visibility"]) {

                case "protected" :

                    if (is_subclass_of(get_called_class(), __CLASS__)) {

                        $this->$name = $value;

                    } else {' .

            ReservedKeywords::FAILURE_VARIABLE . '[] = "accessing $name in an invalid way";' .
            Placeholders::ENFORCEMENT . 'InvalidArgumentException' . Placeholders::PLACEHOLDER_CLOSE .
            '\AppserverIo\Doppelgaenger\ContractContext::close();
            return false;
            }
                    break;

                case "public" :

                    $this->$name = $value;
                    break;

                default :' .

            ReservedKeywords::FAILURE_VARIABLE . '[] = "accessing $name in an invalid way";' .
            Placeholders::ENFORCEMENT . 'InvalidArgumentException' . Placeholders::PLACEHOLDER_CLOSE .
            '\AppserverIo\Doppelgaenger\ContractContext::close();
            return false;
            break;
            }

            // Check if the invariant holds
            ' . Placeholders::INVARIANT . Placeholders::PLACEHOLDER_CLOSE .
            '\AppserverIo\Doppelgaenger\ContractContext::close();';

        // We do not need the method encasing brackets if we inject
        if ($injected === false) {
            $code .= '}';
        }

        return $code;
    }

    /**
     * Will generate the code of the magic __get() method needed to access member variables which are hidden
     * in order to force the usage of __set()
     *
     * @param boolean $hasParents Does this structure have parents
     * @param boolean $injected   Will the created method be injected or is it a stand alone method?
     *
     * @return string
     */
    protected function generateGetCode($hasParents, $injected = false)
    {

        // We only need the method header if we don't inject
        if ($injected === false) {
            $code = '/**
         * Magic function to forward reading property access calls if within visibility boundaries.
         *
         * @throws \Exception
         */
        public function __get($name)
        {';
        } else {
            $code = '';
        }
        $code .= ReservedKeywords::FAILURE_VARIABLE . ' = array();
            ' . ReservedKeywords::UNWRAPPED_FAILURE_VARIABLE . ' = array();
            // Does this property even exist? If not, throw an exception
            if (!isset($this->' . ReservedKeywords::ATTRIBUTE_STORAGE . '[$name])) {';

        if ($hasParents) {
            $code .= 'return parent::__get($name);';
        } else {
            $code .= 'if (property_exists($this, $name)) {' .

                ReservedKeywords::FAILURE_VARIABLE . '[] = "accessing $name in an invalid way";' .
                Placeholders::ENFORCEMENT . 'InvalidArgumentException' . Placeholders::PLACEHOLDER_CLOSE .
                '\AppserverIo\Doppelgaenger\ContractContext::close();
                return false;
                } else {' .

                ReservedKeywords::FAILURE_VARIABLE . '[] = "accessing $name as it does not exist";' .
                Placeholders::ENFORCEMENT . 'MissingPropertyException' . Placeholders::PLACEHOLDER_CLOSE .
                '\AppserverIo\Doppelgaenger\ContractContext::close();
                return false;
                }';
        }

        $code .= '}

        // Now check what kind of visibility we would have
        $attribute = $this->' . ReservedKeywords::ATTRIBUTE_STORAGE . '[$name];
        switch ($attribute["visibility"]) {

            case "protected" :

                if (is_subclass_of(get_called_class(), __CLASS__)) {

                    return $this->$name;

                } else {' .

            ReservedKeywords::FAILURE_VARIABLE . '[] = "accessing $name in an invalid way";' .
            Placeholders::ENFORCEMENT . 'InvalidArgumentException' . Placeholders::PLACEHOLDER_CLOSE .
            '\AppserverIo\Doppelgaenger\ContractContext::close();
            return false;}
                break;

            case "public" :

                return $this->$name;
                break;

            default :' .

            ReservedKeywords::FAILURE_VARIABLE . '[] = "accessing $name in an invalid way";' .
            Placeholders::ENFORCEMENT . 'InvalidArgumentException' . Placeholders::PLACEHOLDER_CLOSE .
            '\AppserverIo\Doppelgaenger\ContractContext::close();
            return false;
            break;
        }';

        // We do not need the method encasing brackets if we inject
        if ($injected === false) {
            $code .= '}';
        }

        return $code;
    }

    /**
     * Will inject the call to the invariant checking method at encountered placeholder strings within the passed
     * bucket data
     *
     * @param string $bucketData Payload of the currently filtered bucket
     *
     * @return boolean
     */
    protected function injectInvariantCall(& $bucketData)
    {
        $code = 'if (' . ReservedKeywords::CONTRACT_CONTEXT . ' === true) {
            $this->' . ReservedKeywords::CLASS_INVARIANT . '(__METHOD__);}';

        // Still here? Then inject the clone statement to preserve an instance of the object prior to our call.
        $bucketData = str_replace(
            Placeholders::INVARIANT . Placeholders::PLACEHOLDER_CLOSE,
            $code,
            $bucketData
        );

        // Still here? We encountered no error then.
        return true;
    }

    /**
     * Will generate the code needed to enforce made invariant assertions
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Lists\TypedListList $assertionLists List of assertion lists
     *
     * @return string
     */
    protected function generateFunctionCode(TypedListList $assertionLists)
    {
        $code = 'protected function ' . ReservedKeywords::CLASS_INVARIANT . '($callingMethod) {' .
            ReservedKeywords::CONTRACT_CONTEXT . ' = \AppserverIo\Doppelgaenger\ContractContext::open();
            if (' . ReservedKeywords::CONTRACT_CONTEXT . ') {
            ' . ReservedKeywords::FAILURE_VARIABLE . ' = array();
            ' . ReservedKeywords::UNWRAPPED_FAILURE_VARIABLE . ' = array();';

        $conditionCounter = 0;
        $invariantIterator = $assertionLists->getIterator();
        for ($i = 0; $i < $invariantIterator->count(); $i++) {
            // Create the inner loop for the different assertions
            if ($invariantIterator->current()->count() !== 0) {
                $assertionIterator = $invariantIterator->current()->getIterator();

                // collect all assertion code for assertions of this instance
                for ($j = 0; $j < $assertionIterator->count(); $j++) {
                    // Code to catch failed assertions
                    $code .= $assertionIterator->current()->toCode();
                    $assertionIterator->next();
                    $conditionCounter++;
                }

                // generate the check for assertions results
                if ($conditionCounter > 0) {
                    $code .= 'if (!empty(' . ReservedKeywords::FAILURE_VARIABLE . ') || !empty(' . ReservedKeywords::UNWRAPPED_FAILURE_VARIABLE .')) {' .
                        Placeholders::ENFORCEMENT . 'invariant' . Placeholders::PLACEHOLDER_CLOSE . '
                }';
                }
            }

            // increment the outer loop
            $invariantIterator->next();
        }

        $code .= '}\AppserverIo\Doppelgaenger\ContractContext::close();}';

        return $code;
    }
}
