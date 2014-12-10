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
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\Entities;

/**
 * AppserverIo\Doppelgaenger\Entities\Joinpoint
 *
 * Definition of a joinpoint.
 * Specifies a certain point within a code structure where an advice might be weaved in
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 *
 * @property string $codeHook   The actual point within the targeted piece of code e.g. "Before"
 * @property string $structure  Structure at which the joinpoint resides
 * @property string $target     At which part of the housing structure does the joinpoint sit?
 * @property string $targetName Name of the target which describes the targeted piece of code
*/
class Joinpoint
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

    /**
     * Getter for the $codeHook property
     *
     * @return string
     */
    public function getCodeHook()
    {
        return $this->codeHook;
    }

    /**
     * Getter for the $structure property
     *
     * @return string
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * Getter for the $target property
     *
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Getter for the $targetName property
     *
     * @return string
     */
    public function getTargetName()
    {
        return $this->targetName;
    }

    /**
     * Setter for the $codeHook property
     *
     * @param string $codeHook The actual point within the targeted piece of code e.g. "Before"
     *
     * @return null
     */
    public function setCodeHook($codeHook)
    {
        $this->codeHook = $codeHook;
    }

    /**
     * Setter for the $structure property
     *
     * @param string $structure Structure at which the joinpoint resides
     *
     * @return null
     */
    public function setStructure($structure)
    {
        $this->structure = $structure;
    }

    /**
     * Setter for the $target property
     *
     * @param string $target At which part of the housing structure does the joinpoint sit?
     *
     * @return null
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * Setter for the $targetName property
     *
     * @param string $targetName Name of the target which describes the targeted piece of code
     *
     * @return null
     */
    public function setTargetName($targetName)
    {
        $this->targetName = $targetName;
    }
}
