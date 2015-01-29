<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Definitions\Advice
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

namespace AppserverIo\Doppelgaenger\Entities\Definitions;

use AppserverIo\Doppelgaenger\Entities\Lists\TypedList;

/**
 * Basic entity class which holds an advice representation
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 *
 * @property string                                              $aspectName Name of the aspect the advice is defined in
 * @property string                                              $codeHook   The code hook this advice is designed for
 * @property string                                              $name       Name of the advice itself
 * @property \AppserverIo\Doppelgaenger\Entities\Lists\TypedList $pointcuts  List of pointcuts referenced by this advice
 */
class Advice
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
     * Default constructor
     */
    public function __construct()
    {
        $this->pointcuts = new TypedList('\AppserverIo\Doppelgaenger\Interfaces\PointcutInterface');
    }

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
     * Getter for the $pointcuts property
     *
     * @return \AppserverIo\Doppelgaenger\Entities\Lists\TypedList
     */
    public function getPointcuts()
    {
        return $this->pointcuts;
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

    /**
     * Setter for the $aspectName property
     *
     * @param string $aspectName Name of the aspect the advice is defined in
     *
     * @return null
     */
    public function setAspectName($aspectName)
    {
        $this->aspectName = $aspectName;
    }

    /**
     * Setter for the $codeHook property
     *
     * @param string $codeHook The code hook this advice is designed for
     *
     * @return null
     */
    public function setCodeHook($codeHook)
    {
        $this->codeHook = $codeHook;
    }

    /**
     * Setter for the $name property
     *
     * @param string $name Name of the advice itself
     *
     * @return null
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
