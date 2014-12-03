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
 * @subpackage StreamFilters
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\StreamFilters;

use AppserverIo\Doppelgaenger\Exceptions\ExceptionFactory;
use AppserverIo\Doppelgaenger\Exceptions\GeneratorException;
use AppserverIo\Doppelgaenger\Dictionaries\Placeholders;
use AppserverIo\Doppelgaenger\Dictionaries\ReservedKeywords;

/**
 * AppserverIo\Doppelgaenger\StreamFilters\EnforcementFilter
 *
 * This filter will buffer the input stream and add the processing information into the prepared assertion checks
 * (see $dependencies)
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage StreamFilters
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
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
    protected $dependencies = array('PreconditionFilter', 'PostconditionFilter', 'InvariantFilter');

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
        $config = $this->params;

        if ($config->hasValue('enforcement/processing')) {

            throw new GeneratorException('Configuration does not contain the needed processing section.');
        }

        // Get the code for the processing
        $preconditionCode = $this->generateCode($config, 'precondition');
        $postconditionCode = $this->generateCode($config, 'postcondition');
        $invariantCode = $this->generateCode($config, 'invariant');
        $invalidCode = $this->generateCode($config, 'InvalidArgumentException');
        $missingCode = $this->generateCode($config, 'MissingPropertyException');

        // Get our buckets from the stream
        while ($bucket = stream_bucket_make_writeable($in)) {

            // Insert the code for the static processing placeholders
            $bucket->data = str_replace(
                array(
                    Placeholders::PROCESSING . 'precondition' . Placeholders::PLACEHOLDER_CLOSE,
                    Placeholders::PROCESSING . 'postcondition' . Placeholders::PLACEHOLDER_CLOSE,
                    Placeholders::PROCESSING . 'invariant' . Placeholders::PLACEHOLDER_CLOSE,
                    Placeholders::PROCESSING . 'InvalidArgumentException' . Placeholders::PLACEHOLDER_CLOSE,
                    Placeholders::PROCESSING . 'MissingPropertyException' . Placeholders::PLACEHOLDER_CLOSE
                ),
                array($preconditionCode, $postconditionCode, $invariantCode, $invalidCode, $missingCode),
                $bucket->data
            );

            // Tell them how much we already processed, and stuff it back into the output
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }

        return PSFS_PASS_ON;
    }

    /**
     * /**
     * Will generate the code needed to enforce any broken assertion checks
     *
     * @param array  $config The configuration aspect which holds needed information for us
     * @param string $for    For which kind of assertion do wee need the processing
     *
     * @return string
     */
    private function generateCode($config, $for)
    {
        $code = '';

        // Code defining the place the error happened
        $place = '__METHOD__';

        // If we are in an invariant we should tell them about the method we got called from
        if ($for === 'invariant') {

            $place = '$callingMethod';
        }

        // What kind of reaction should we create?
        switch ($config->getValue('enforcement/processing')) {

            case 'exception':

                $exceptionFactory = new ExceptionFactory();
                $exception = $exceptionFactory->getClassName($for);

                // Create the code
                $code .= '\AppserverIo\Doppelgaenger\ContractContext::close();
                throw new \\' . $exception . '("Failed ' . ReservedKeywords::FAILURE_VARIABLE . ' in " . ' . $place . ');';

                break;

            case 'logging':

                // Create the code
                $code .= '$container = new \AppserverIo\Doppelgaenger\Utils\InstanceContainer();
                $logger = $container[' . ReservedKeywords::LOGGER_CONTAINER_ENTRY . '];
                $logger->error("Failed ' . $for .
                    ReservedKeywords::FAILURE_VARIABLE . ' in " . ' . $place . ');';
                break;

            default:

                break;
        }

        return $code;
    }
}
