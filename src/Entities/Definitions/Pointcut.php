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
 * AppserverIo\Doppelgaenger\Entities\Definitions\Pointcut
 *
 * Definition class which represents a pointcut which got implemented as a referenceable piece of code within an advice
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 *
 * @property string                                                 $aspectName         Name Name of the aspect the advice is defined in
 * @property string                                                 $name               Name of function representing the pointcut within code
 * @property \AppserverIo\Doppelgaenger\Entities\PointcutExpression $pointcutExpression Expression defining the target of advices referencing this pointcut
 */
class Pointcut extends AbstractLockableEntity
{
    /**
     * Name of the aspect the advice is defined in
     *
     * @var string $aspectName
     */
    protected $aspectName;

    /**
     * Name of function representing the pointcut within code
     *
     * @var string $name
     */
    protected $name;

    /**
     * Expression defining the target of advices referencing this pointcut
     *
     * @var \AppserverIo\Doppelgaenger\Entities\PointcutExpression $pointcutExpression
     */
    protected $pointcutExpression;

    /**
     * Getter for the $pointcutExpression property
     *
     * @return \AppserverIo\Doppelgaenger\Entities\PointcutExpression
     */
    public function getPointcutExpression()
    {
        return $this->pointcutExpression;
    }

    /**
     * Will return the qualified name of an pointcut.
     * Will have the form or <CONTAINING ASPECT>-><POINTCUT NAME>
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
