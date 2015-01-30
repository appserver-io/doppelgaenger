<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Aspect
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
 * Class which represents the definition of an aspect as an annotated class definition.
 * Will only contain the needed parts of the definition
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 *
 * @property \AppserverIo\Doppelgaenger\Entities\Lists\TypedList $advices   List of advices
 * @property string                                              $name      Name of the aspect
 * @property string                                              $namespace Namespace of this aspect definition
 * @property \AppserverIo\Doppelgaenger\Entities\Lists\TypedList $pointcuts List of pointcut definitions
 */
class Aspect
{

    /**
     * List of advices (\AppserverIo\Doppelgaenger\Entities\Definitions\Advice) which are defined within
     * the aspect definition
     *
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\TypedList $advices
     */
    protected $advices;

    /**
     * Name of the aspect
     *
     * @var string $name
     */
    protected $name;

    /**
     * Namespace of this aspect definition
     *
     * @var string $namespace
     */
    protected $namespace;

    /**
     * List of pointcut definitions (\AppserverIo\Doppelgaenger\Entities\Definitions\Pointcut) which are defined within
     * the code of this aspect
     *
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\TypedList $pointcuts
     */
    protected $pointcuts;

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->pointcuts = new TypedList('\AppserverIo\Doppelgaenger\Entities\Definitions\Pointcut', 'name');
        $this->advices = new TypedList('\AppserverIo\Doppelgaenger\Entities\Definitions\Advice', 'name');
    }

    /**
     * Getter for the $advices property
     *
     * @return \AppserverIo\Doppelgaenger\Entities\Lists\TypedList
     */
    public function getAdvices()
    {
        return $this->advices;
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
     * Getter for the $namespace property
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
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
     * Will return the qualified name of a structure
     *
     * @return string
     */
    public function getQualifiedName()
    {
        if (empty($this->namespace)) {
            return $this->name;

        } else {
            return $this->namespace . '\\' . $this->name;
        }
    }

    /**
     * Setter for the $name property
     *
     * @param string $name Name of the aspect
     *
     * @return null
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Setter for the $namespace property
     *
     * @param string $namespace Namespace of this aspect definition
     *
     * @return null
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }
}
