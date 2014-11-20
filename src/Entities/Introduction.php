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
 * AppserverIo\Doppelgaenger\Entities\Introduction
 *
 * Class which represents the introduction of additional characteristics to a target class
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class Introduction extends AbstractLockableEntity
{

    /**
     * Name of a trait which is used to provide an implementation of the introduces interface.
     * Must be fully qualified or already known to the target's namespace
     *
     * @var string $implementation
     */
    protected $implementation;

    /**
     * Name of the interface which is used to extend the target's characteristics.
     * Must be fully qualified or already known to the target's namespace
     *
     * @var string $interface
     */
    protected $interface;

    /**
     * Name of the target class which gets new characteristics introduced
     * Might also be a PCRE which will match several classes
     *
     * @var  $target <REPLACE WITH FIELD COMMENT>
     */
    protected $target;
}
