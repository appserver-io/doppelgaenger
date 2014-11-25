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

namespace AppserverIo\Doppelgaenger\Entities\Definitions;

use AppserverIo\Doppelgaenger\Entities\AbstractLockableEntity;

/**
 * AppserverIo\Doppelgaenger\Entities\Definitions\Advice
 *
 * Basic entity class which holds an advice representation
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 *
 * @property string                                              $aspectName Name of the aspect the advice is defined in
 * @property string                                              $codeHook   The code hook this advice is designed for
 * @property string                                              $name       Name of the advice itself
 * @property \AppserverIo\Doppelgaenger\Entities\Lists\TypedList $pointcuts  List of pointcuts referenced by this advice
 */
class Advice extends AbstractLockableEntity
{

    /**
     * Name of the aspect the advice is defined in
     *
     * @var string $aspectName
     */
    protected $aspectName;

    /**
     * The code hook this advice is designed for
     *
     * @var string $codeHook
     *
     * @Enum({"After", "AfterReturning", "AfterThrowing", "Around", "Before"})
     */
    protected $codeHook;

    /**
     * Name of the advice itself
     *
     * @var string $name
     */
    protected $name;

    /**
     * List of pointcuts referenced by this advice
     *
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\TypedList $pointcuts
     */
    protected $pointcuts;

    /**
     * Getter for the $aspectName property
     *
     * @return string
     */
    public function getAspectName()
    {
        return $this->aspectName;
    }

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
     * Getter for the $name property
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Will return the qualified name of an advice.
     * Will have the form or <CONTAINING ASPECT>-><ADVICE NAME>
     *
     * @return string
     */
    public function getQualifiedName()
    {
        if (empty($this->aspectName)) {

            return $this->name;

        } else {

            return $this->aspectName . '->' . $this->name;
        }
    }
}
