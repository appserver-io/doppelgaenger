<?php

/**
 * \AppserverIo\Doppelgaenger\Dictionaries\ReservedKeywords
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

namespace AppserverIo\Doppelgaenger\Dictionaries;

/**
 * Contains reserved variable, property and function names on which basic design by contract functionality relies
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class ReservedKeywords
{

    /**
     * Reserved property name which is used as a storage for contracted attributes/properties
     *
     * @var string ATTRIBUTE_STORAGE
     */
    const ATTRIBUTE_STORAGE = 'dgAttributes';

    /**
     * Reserved name of the function wrapping invariant checks
     *
     * @var string CLASS_INVARIANT
     */
    const CLASS_INVARIANT = 'dgClassInvariant';

    /**
     * Reserved local variable containing a flag which indications if we currently are within a contract
     *
     * @var string CONTRACT_CONTEXT
     */
    const CONTRACT_CONTEXT = '$dgOngoingContract';

    /**
     * Reserved local variable which is used to build up messages regarding failed contracts
     *
     * @var string FAILURE_VARIABLE
     */
    const FAILURE_VARIABLE = '$dgFailureMessage';

    /**
     * Reserved local variable which is used to build up messages regarding failed contracts.
     * Messages contained in this variable MUST NOT be wrapped
     *
     * @var string UNWRAPPED_FAILURE_VARIABLE
     */
    const UNWRAPPED_FAILURE_VARIABLE = '$dgUnwrappedFailureMessage';

    /**
     * Reserved local variable containing a flag which indications if we currently are within a contract
     *
     * @var string LOGGER_CONTAINER_ENTRY
     */
    const LOGGER_CONTAINER_ENTRY = 'DOPPELGAENGER_LOGGER_CONTAINER_ENTRY';

    /**
     * Placeholder for inserting the invariant checks
     *
     * @var string MARK_CONTRACT_ENTRY
     */
    const MARK_CONTRACT_ENTRY = '$dgContractEntry';

    /**
     * Name of the local variable representing the method invocation within woven advices
     *
     * @var string METHOD_INVOCATION_OBJECT
     */
    const METHOD_INVOCATION_OBJECT = '$dgMethodInvocationObject';

    /**
     * Reserved local variable containing a scope copy before function execution
     *
     * @var string OLD
     */
    const OLD = '$dgOld';

    /**
     * Suffix which identifies the original implementation of a function
     *
     * @var string ORIGINAL_FUNCTION_SUFFIX
     */
    const ORIGINAL_FUNCTION_SUFFIX = 'DOPPELGAENGEROriginal';

    /**
     * Variable locally used as a flag for handling assertion flow
     *
     * @var string PASSED_ASSERTION_FLAG
     */
    const PASSED_ASSERTION_FLAG = '$dgPassedBlock';

    /**
     * Reserved local variable containing the result of the actual function execution
     *
     * @var string RESULT
     */
    const RESULT = '$dgResult';

    /**
     * Name of the local variable representing the exception thrown during method execution
     *
     * @var string THROWN_EXCEPTION_OBJECT
     */
    const THROWN_EXCEPTION_OBJECT = '$dgThrownExceptionObject';
}
