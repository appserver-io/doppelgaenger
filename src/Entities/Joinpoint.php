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
 * @package    AppserverIo_Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Entities;

/**
 * AppserverIo\Doppelgaenger\Entities\Joinpoint
 *
 * Definition of a joinpoint.
 * Specifies a certain point within a code structure where an advice might be weaved in
 *
 * @category   Appserver
 * @package    AppserverIo_Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class Joinpoint extends AbstractLockableEntity
{

    /**
     * Constant which holds the identifier for one possible target of a joinpoint
     *
     * @var string TARGET_METHOD
     */
    const TARGET_METHOD = 'Method';

    /**
     * Constant which holds the identifier for one possible target of a joinpoint
     *
     * @var string TARGET_PROPERTY
     */
    const TARGET_PROPERTY = 'Property';

    /**
     * Constant which holds the identifier for one possible target of a joinpoint
     *
     * @var string TARGET_STRUCTURE
     */
    const TARGET_STRUCTURE = 'Structure';

    /**
     * The actual point within the targeted piece of code e.g. "Before"
     *
     * @var string $codeHook
     *
     * @Enum({"After", "AfterReturning", "AfterThrowing", "Around", "Before"})
     */
    protected $codeHook;

    /**
     * Structure at which the joinpoint resides
     *
     * @var string $structure
     */
    protected $structure;

    /**
     * At which part of the housing structure does the joinpoint sit?
     * Possible values as mentioned below in the @Enum annotation
     *
     * @var string $target
     *
     * @Enum({"Method", "Property", "Structure"})
     */
    protected $target;

    /**
     * Name of the target which describes the targeted piece of code, e.g. a method or property name
     *
     * @var string $targetName
     */
    protected $targetName;
}
