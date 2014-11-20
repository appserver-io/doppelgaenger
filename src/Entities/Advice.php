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
 * AppserverIo\Doppelgaenger\Entities\Advice
 *
 * Basic entity class which holds an advice representation
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
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
     * The
     *
     * @var  $pointcuts
     */
    protected $pointcuts;

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
