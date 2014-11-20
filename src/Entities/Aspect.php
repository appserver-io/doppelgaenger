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

use AppserverIo\Doppelgaenger\Entities\Lists\TypedList;

/**
 * AppserverIo\Doppelgaenger\Entities\Aspect
 *
 * <TODO CLASS DESCRIPTION>
 *
 * @category  Library
 * @package   Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class Aspect extends AbstractLockableEntity
{

    public function __construct()
    {
        $this->pointcuts = new TypedList('\AppserverIo\Doppelgaenger\Entities\Definitions\Pointcut', 'name');
        $this->advices = new TypedList('\AppserverIo\Doppelgaenger\Entities\Advice', 'name');
    }

    protected $advices;

protected $name;
    protected $namespace;

    protected $pointcuts;

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
