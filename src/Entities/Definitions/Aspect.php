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
use AppserverIo\Doppelgaenger\Entities\Lists\TypedList;

/**
 * AppserverIo\Doppelgaenger\Entities\Aspect
 *
 * Class which represents the definition of an aspect as an annotated class definition.
 * Will only contain the needed parts of the definition
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class Aspect extends AbstractLockableEntity
{

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->pointcuts = new TypedList('\AppserverIo\Doppelgaenger\Entities\Definitions\Pointcut', 'name');
        $this->advices = new TypedList('\AppserverIo\Doppelgaenger\Entities\Definitions\Advice', 'name');
    }

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
}
