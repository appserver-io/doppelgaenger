<?php

/**
 * \AppserverIo\Doppelgaenger\Dictionaries\Placeholders
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
 * Contains constants which are used as placeholders during stream filter processing
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
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
     * Placeholder for any "after returning" advices which might be weaved in
     *
     * @var string AFTERRETURNING_JOINPOINT
     */
    const AFTERRETURNING_JOINPOINT = '/* DOPPELGAENGER_AFTERRETURNING_JOINPOINT ';

    /**
     * Placeholder for any "after throwing" advices which might be weaved in
     *
     * @var string AFTERTHROWING_JOINPOINT
     */
    const AFTERTHROWING_JOINPOINT = '/* DOPPELGAENGER_AFTERTHROWING_JOINPOINT ';

    /**
     * Placeholder for any "around" advice which might be weaved in
     *
     * @var string AROUND_JOINPOINT
     */
    const AROUND_JOINPOINT = '/* DOPPELGAENGER_AROUND_JOINPOINT ';

    /**
     * Placeholder for any "before" advices which might be weaved in
     *
     * @var string BEFORE_JOINPOINT
     */
    const BEFORE_JOINPOINT = '/* DOPPELGAENGER_BEFORE_JOINPOINT ';

    /**
     * Placeholder for any structure constants we might want to weave in
     *
     * @var string CONSTANT_HOOK
     */
    const CONSTANT_HOOK = '/* DOPPELGAENGER_CONSTANT_HOOK */';

    /**
     * Placeholder to insert the actual processing of needed contract enforcing
     *
     * @var string ENFORCEMENT
     */
    const ENFORCEMENT = '/* DOPPELGAENGER_ENFORCEMENT_PLACEHOLDER ';

    /**
     * Placeholder for a hook right after the structure head at which functions might be inserted
     *
     * @var string FUNCTION_HOOK
     */
    const FUNCTION_HOOK = '/* DOPPELGAENGER_FUNCTION_HOOK_PLACEHOLDER ';

    /**
     * Placeholder for injection of additional code, etc.
     * Placed right after the beginning of the function body
     *
     * @var string FUNCTION_BEGIN
     */
    const FUNCTION_BEGIN = '/* DOPPELGAENGER_FUNCTION_BEGIN_PLACEHOLDER ';

    /**
     * Placeholder for inserting the invariant checks at the end of a construct
     *
     * @var string INVARIANT_CALL_END
     */
    const INVARIANT_CALL_END = '/* DOPPELGAENGER_INVARIANT_CALL_END_PLACEHOLDER */';

    /**
     * Placeholder for inserting the invariant checks at random places
     *
     * @var string INVARIANT_CALL
     */
    const INVARIANT_CALL = '/* DOPPELGAENGER_INVARIANT_CALL_PLACEHOLDER */';

    /**
     * Placeholder for inserting the invariant checks at the start of a construct
     *
     * @var string INVARIANT_CALL_START
     */
    const INVARIANT_CALL_START = '/* DOPPELGAENGER_INVARIANT_CALL_START_PLACEHOLDER */';

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
     * String opening generic placeholders to allow filling dynamic parts into them
     *
     * @var string PLACEHOLDER_OPEN
     */
    const PLACEHOLDER_OPEN = '/* ';

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
     * Placeholder for injection of additional methods, properties, etc.
     * Placed right after the beginning of the structure body
     *
     * @var string STRUCTURE_BEGIN
     */
    const STRUCTURE_BEGIN = '/* DOPPELGAENGER_STRUCTURE_BEGIN_PLACEHOLDER */';

    /**
     * Placeholder for injection of additional methods, properties, etc.
     * Placed right before the end of the structure body
     *
     * @var string STRUCTURE_END
     */
    const STRUCTURE_END = '/* DOPPELGAENGER_STRUCTURE_END_PLACEHOLDER */';

    /**
     * Placeholder for additional inheritance or interfaces to implement.
     * Placed right before the beginning of the structure body
     *
     * @var string STRUCTURE_HEADER
     */
    const STRUCTURE_HEADER = '/* DOPPELGAENGER_STRUCTURE_HEADER_PLACEHOLDER */';
}
