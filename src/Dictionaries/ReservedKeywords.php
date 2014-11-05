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
 * @category   Appserver
 * @package    AppserverIo\Doppelgaenger
 * @subpackage Dictionaries
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Dictionaries;

/**
 * AppserverIo\Doppelgaenger\Dictionaries\ReservedKeywords
 *
 * Contains reserved variable, property and function names on which basic design by contract functionality relies
 *
 * @category   Appserver
 * @package    AppserverIo\Doppelgaenger
 * @subpackage Dictionaries
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class ReservedKeywords
{
    /**
     * Reserved property name which is used as a storage for contracted attributes/properties
     *
     * @var string ATTRIBUTE_STORAGE
     */
    const ATTRIBUTE_STORAGE = 'pbcAttributes';

    /**
     * Reserved name of the function wrapping invariant checks
     *
     * @var string CLASS_INVARIANT
     */
    const CLASS_INVARIANT = 'pbcClassInvariant';

    /**
     * Reserved local variable containing a flag which indications if we currently are within a contract
     *
     * @var string CONTRACT_CONTEXT
     */
    const CONTRACT_CONTEXT = '$pbcOngoingContract';

    /**
     * Reserved constant name which is used for a substitute of the __DIR__ constant
     *
     * @var string DIR_SUBSTITUTE
     */
    const DIR_SUBSTITUTE = 'DOPPELGAENGER_DIR_SUBSTITUTE';

    /**
     * Reserved local variable which is used to build up messages regarding failed contracts
     *
     * @var string FAILURE_VARIABLE
     */
    const FAILURE_VARIABLE = '$pbcFailureMessage';

    /**
     * Reserved constant name which is used for a substitute of the __FILE__ constant
     *
     * @var string FILE_SUBSTITUTE
     */
    const FILE_SUBSTITUTE = 'DOPPELGAENGER_FILE_SUBSTITUTE';

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
    const MARK_CONTRACT_ENTRY = '$pbcContractEntry';

    /**
     * Reserved local variable containing a scope copy before function execution
     *
     * @var string OLD
     */
    const OLD = '$pbcOld';

    /**
     * Suffix which identifies the original implementation of a function
     *
     * @var string ORIGINAL_FUNCTION_SUFFIX
     */
    const ORIGINAL_FUNCTION_SUFFIX = 'DOPPELGAENGEROriginal';

    /**
     * Reserved local variable containing the result of the actual function execution
     *
     * @var string RESULT
     */
    const RESULT = '$pbcResult';
}
