<?php

/**
 * \AppserverIo\Doppelgaenger\StreamFilters\EnforcementFilter
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

use AppserverIo\Doppelgaenger\Config;
use AppserverIo\Doppelgaenger\Entities\Definitions\FunctionDefinition;
use AppserverIo\Doppelgaenger\Exceptions\ExceptionFactory;
use AppserverIo\Doppelgaenger\Exceptions\GeneratorException;
use AppserverIo\Doppelgaenger\Dictionaries\Placeholders;
use AppserverIo\Doppelgaenger\Dictionaries\ReservedKeywords;
use AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface;
use AppserverIo\Psr\MetaobjectProtocol\Dbc\Annotations\Processing;

/**
 * This filter will buffer the input stream and add the processing information into the prepared assertion checks
 * (see $dependencies)
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class EnforcementFilter extends AbstractFilter
{

    /**
     * Order number if filters are used as a stack, higher means below others
     *
     * @const integer FILTER_ORDER
     */
    const FILTER_ORDER = 4;

    /**
     * Other filters on which we depend
     *
     * @var array $dependencies
     */
    protected $dependencies = array(array('PreconditionFilter', 'PostconditionFilter', 'InvariantFilter'));

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
        // Lets check if we got the config we wanted
        $config = $this->params['config'];
        $structureDefinition = $this->params['structureDefinition'];

        // check if we got what we need for proper processing
        if (!$config instanceof Config || !$structureDefinition instanceof StructureDefinitionInterface) {
            throw new GeneratorException('The enforcement filter needs the configuration as well as the definition of the currently filtered structure. At least one of these requirements is missing.');
        }

        // we need a valid configuration as well
        if (!$config->hasValue('enforcement/processing')) {
            throw new GeneratorException('Configuration does not contain the needed processing section.');
        }

        // get the default enforcement processing
        $localType = $this->filterLocalProcessing($structureDefinition->getDocBlock());
        $type = $localType ? $localType : $config->getValue('enforcement/processing');

        // Get the code for the processing
        $structureName = $structureDefinition->getQualifiedName();
        $structurePath = $structureDefinition->getPath();
        $preconditionCode = $this->generateCode($structureName, 'precondition', $type, $structurePath);
        $postconditionCode = $this->generateCode($structureName, 'postcondition', $type, $structurePath);
        $invariantCode = $this->generateCode($structureName, 'invariant', $type, $structurePath);
        $invalidCode = $this->generateCode($structureName, 'InvalidArgumentException', $type, $structurePath);
        $missingCode = $this->generateCode($structureName, 'MissingPropertyException', $type, $structurePath);

        // Get our buckets from the stream
        while ($bucket = stream_bucket_make_writeable($in)) {
            // Get the tokens
            $tokens = token_get_all($bucket->data);

            // Go through the tokens and check what we found
            $tokensCount = count($tokens);
            for ($i = 0; $i < $tokensCount; $i++) {
                // Did we find a function? If so check if we know that thing and insert the code of its preconditions.
                if (is_array($tokens[$i]) && $tokens[$i][0] === T_FUNCTION && is_array($tokens[$i + 2])) {
                    // Get the name of the function
                    $functionName = $tokens[$i + 2][1];

                    // Check if we got the function in our list, if not continue
                    $functionDefinition = $structureDefinition->getFunctionDefinitions()->get($functionName);

                    if (!$functionDefinition instanceof FunctionDefinition) {
                        continue;

                    } else {
                        // manage the injection of the enforcement code into the found function
                        $this->injectFunctionEnforcement(
                            $bucket->data,
                            $structureName,
                            $structurePath,
                            $preconditionCode,
                            $postconditionCode,
                            $functionDefinition
                        );

                        // "Destroy" code and function definition
                        $functionDefinition = null;
                    }
                }
            }

            // Insert the code for the static processing placeholders
            $bucket->data = str_replace(
                array(
                    Placeholders::ENFORCEMENT . 'invariant' . Placeholders::PLACEHOLDER_CLOSE,
                    Placeholders::ENFORCEMENT . 'InvalidArgumentException' . Placeholders::PLACEHOLDER_CLOSE,
                    Placeholders::ENFORCEMENT . 'MissingPropertyException' . Placeholders::PLACEHOLDER_CLOSE
                ),
                array($invariantCode, $invalidCode, $missingCode),
                $bucket->data
            );

            // Tell them how much we already processed, and stuff it back into the output
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }

        return PSFS_PASS_ON;
    }

    /**
     * Will try to filter custom local enforcement processing from a given docBloc.
     * Will return the found value, FALSE otherwise
     *
     * @param string $docBlock DocBloc to filter
     *
     * @return boolean|string
     */
    protected function filterLocalProcessing($docBlock)
    {
        // if the annotation cannot be found we have to do nothing here
        if (strpos($docBlock, '@' . Processing::ANNOTATION) === false) {
            return false;
        }

        // try to preg_match the right annotation
        $matches = array();
        preg_match_all('/@' . Processing::ANNOTATION . '\("(.+)"\)/', $docBlock, $matches);
        if (isset($matches[1])) {
            return array_pop($matches[1]);
        }

        // still here? Tell them we have failed then
        return false;
    }

    /**
     * /**
     * Will generate the code needed to enforce any broken assertion checks
     *
     * @param string $structureName The name of the structure for which we create the enforcement code
     * @param string $target        For which kind of assertion do wee need the processing
     * @param string $type          The enforcement processing type to generate code for
     * @param string $file          File for which the code gets generated
     *
     * @return string
     *
     * @throws \AppserverIo\Doppelgaenger\Exceptions\GeneratorException
     */
    protected function generateCode($structureName, $target, $type, $file = 'unknown')
    {
        $code = '';

        // Code defining the place the error happened
        $place = '__METHOD__';

        // If we are in an invariant we should tell them about the method we got called from
        $line = ReservedKeywords::START_LINE_VARIABLE;
        if ($target === 'invariant') {
            $place = ReservedKeywords::INVARIANT_CALLER_VARIABLE;
            $line = ReservedKeywords::ERROR_LINE_VARIABLE;

        } elseif ($target === 'postcondition') {
            $line = ReservedKeywords::END_LINE_VARIABLE;
        }

        // what we will always need is collection of all errors that occurred
        $errorCollectionCode = 'if (empty(' . ReservedKeywords::FAILURE_VARIABLE . ')) {
                        ' . ReservedKeywords::FAILURE_VARIABLE . ' = "";
                    } else {
                        ' . ReservedKeywords::FAILURE_VARIABLE . ' = \'Failed ' . $target . ' "\' . implode(\'" and "\', ' . ReservedKeywords::FAILURE_VARIABLE . ') . \'" in \' . ' . $place . ';
                    }
                    ' . ReservedKeywords::FAILURE_VARIABLE . ' .= implode(" and ", ' . ReservedKeywords::UNWRAPPED_FAILURE_VARIABLE . ');';

        // what kind of processing should we create?
        switch ($type) {
            case 'exception':

                $exceptionFactory = new ExceptionFactory();
                $exception = $exceptionFactory->getClassName($target);

                // Create the code
                $code .= '\AppserverIo\Doppelgaenger\ContractContext::close();
                    ' . $errorCollectionCode . '
                    $e = new \\' . $exception . '(' . ReservedKeywords::FAILURE_VARIABLE . ');
                    if ($e instanceof \AppserverIo\Doppelgaenger\Interfaces\ProxyExceptionInterface) {
                        $e->setLine(' . $line . ');
                        $e->setFile(\'' . $file . '\');
                    }
                    throw $e;';

                break;

            case 'logging':

                // Create the code
                $code .= $errorCollectionCode .
                    '$container = new \AppserverIo\Doppelgaenger\Utils\InstanceContainer();
                    $logger = @$container[\'' . ReservedKeywords::LOGGER_CONTAINER_ENTRY . '\'];
                    if (is_null($logger)) {
                        error_log(' . ReservedKeywords::FAILURE_VARIABLE . ');
                    } else {
                        $logger->error(' . ReservedKeywords::FAILURE_VARIABLE . ');
                    }';

                break;

            case 'none':

                // Create the code
                $code .= '\AppserverIo\Doppelgaenger\ContractContext::close();';
                break;

            default:
                // something went terribly wrong ...
                throw new GeneratorException(
                    sprintf(
                        'Unknown enforcement type "%s", please check configuration value "enforcement/processing" and %s annotations within %s',
                        $type,
                        Processing::ANNOTATION,
                        $structureName
                    )
                );
                break;
        }

        return $code;
    }

    /**
     * Will inject enforcement processing for a certain function.
     * Will take default processing code into account and check for custom processing configurations
     *
     * @param string             $bucketData         Payload of the currently filtered bucket
     * @param string             $structureName      The name of the structure for which we create the enforcement code
     * @param string             $structurePath      Path to the file containing the structure
     * @param string             $preconditionCode   Default precondition processing code
     * @param string             $postconditionCode  Default post-condition processing code
     * @param FunctionDefinition $functionDefinition Function definition to create the code for
     *
     * @return null
     */
    protected function injectFunctionEnforcement(
        & $bucketData,
        $structureName,
        $structurePath,
        $preconditionCode,
        $postconditionCode,
        FunctionDefinition $functionDefinition
    ) {
        $functionName = $functionDefinition->getName();

        // try to find a local enforcement processing configuration, if we find something we have to
        // create new enforcement code based on that information
        $localType = $this->filterLocalProcessing($functionDefinition->getDocBlock());
        if ($localType !== false) {
            // we found something, make a backup of default enforcement and generate the new code
            $preconditionCode = $this->generateCode($structureName, 'precondition', $localType, $structurePath);
            $postconditionCode = $this->generateCode($structureName, 'postcondition', $localType, $structurePath);
        }

        // Insert the code for the static processing placeholders
        $bucketData = str_replace(
            array(
                Placeholders::ENFORCEMENT . $functionName . 'precondition' . Placeholders::PLACEHOLDER_CLOSE,
                Placeholders::ENFORCEMENT . $functionName . 'postcondition' . Placeholders::PLACEHOLDER_CLOSE
            ),
            array($preconditionCode, $postconditionCode),
            $bucketData
        );
    }
}
