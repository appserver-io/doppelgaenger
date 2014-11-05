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
 * AppserverIo\Doppelgaenger\Dictionaries\Placeholders
 *
 * Contains constants which are used as placeholders during stream filter processing
 *
 * @category   Appserver
 * @package    AppserverIo\Doppelgaenger
 * @subpackage Dictionaries
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class Placeholders
{
    /**
     * Placeholder for any "after" advices which might be weaved in
     *
     * @var string AFTER_JOINPOINT
     */
    const AFTER_JOINPOINT = '/* DOPPELGAENGER_AFTER_JOINPOINT ';

    /**
     * Placeholder for any "before" advices which might be weaved in
     *
     * @var string BEFORE_JOINPOINT
     */
    const BEFORE_JOINPOINT = '/* DOPPELGAENGER_BEFORE_JOINPOINT ';

    /**
     * Placeholder for a hook right after the structure head at which functions might be inserted
     *
     * @var string FUNCTION_HOOK
     */
    const FUNCTION_HOOK = '/* DOPPELGAENGER_FUNCTION_HOOK_PLACEHOLDER ';

    /**
     * Placeholder for inserting the invariant checks
     *
     * @var string INVARIANT
     */
    const INVARIANT = '/* DOPPELGAENGER_INVARIANT_PLACEHOLDER ';

    /**
     * Placeholder for injecting additional logic into generated proxy methods
     *
     * @var string METHOD_INJECT
     */
    const METHOD_INJECT = '/* DOPPELGAENGER_METHOD_INJECT_PLACEHOLDER ';

    /**
     * Placeholder for inserting the setup of the reserved "old" variable which contains a copy of $this before
     * method execution
     *
     * @var string OLD_SETUP
     */
    const OLD_SETUP = '/* DOPPELGAENGER_OLD_SETUP_PLACEHOLDER ';

    /**
     * Placeholder for the call to the original method logic
     *
     * @var string ORIGINAL_CALL
     */
    const ORIGINAL_CALL = '/* DOPPELGAENGER_ORIGINAL_CALL_PLACEHOLDER ';

    /**
     * Placeholder for insertion (and marking) of meta-information about the original file
     *
     * @var string ORIGINAL_PATH_HINT
     */
    const ORIGINAL_PATH_HINT = 'DOPPELGAENGER_ORIGINAL_PATH_HINT';

    /**
     * String closing generic placeholders to allow filling dynamic parts into them
     *
     * @var string PLACEHOLDER_CLOSE
     */
    const PLACEHOLDER_CLOSE = ' */';

    /**
     * Placeholder for inserting the postcondition checks
     *
     * @var string POSTCONDITION
     */
    const POSTCONDITION = '/* DOPPELGAENGER_POSTCONDITION_PLACEHOLDER ';

    /**
     * Placeholder for inserting the precondition checks
     *
     * @var string PRECONDITION
     */
    const PRECONDITION = '/* DOPPELGAENGER_PRECONDITION_PLACEHOLDER ';

    /**
     * Placeholder to insert the actual processing of needed contract enforcing
     *
     * @var string PROCESSING
     */
    const PROCESSING = '/* DOPPELGAENGER_PROCESSING_PLACEHOLDER ';

    /**
     * Placeholder for injection of additional methods, properties, etc.
     * Placed right after the beginning of the structure body
     *
     * @var string STRUCTURE_BEGIN
     */
    const STRUCTURE_BEGIN = '/* DOPPELGAENGER_STRUCTURE_BEGIN_PLACEHOLDER */';

    /**
     * Placeholder for additional inheritance or interfaces to implement.
     * Placed right before the beginning of the structure body
     *
     * @var string STRUCTURE_HEADER
     */
    const STRUCTURE_HEADER = '/* DOPPELGAENGER_STRUCTURE_HEADER_PLACEHOLDER */';
}
