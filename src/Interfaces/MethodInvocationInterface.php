<?php

/**
 * \AppserverIo\Doppelgaenger\Interfaces\MethodInvocationInterface
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

namespace AppserverIo\Doppelgaenger\Interfaces;

/**
 * Interface describing a class which acts as a DTO to transfer information about any invoked method
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
interface MethodInvocationInterface
{

    /**
     * Getter method for property $context
     *
     * @return object
     */
    public function getContext();

    /**
     * Getter method for property $name
     *
     * @return string
     */
    public function getName();

    /**
     * Getter for the result of the method invocation
     *
     * @return mixed
     */
    public function getResult();

    /**
     * Getter method for property $parameters
     *
     * @return array
     */
    public function getParameters();

    /**
     * Getter method for property $structureName
     *
     * @return string
     */
    public function getStructureName();

    /**
     * Getter method for property $thrownException
     *
     * @return string
     */
    public function getThrownException();

    /**
     * Getter method for property $visibility
     *
     * @return string
     */
    public function getVisibility();

    /**
     * Will be used to inject the result of the original method
     *
     * @param mixed $result The result to inject
     *
     * @return mixed
     */
    public function injectResult($result);

    /**
     * Used to injection the thrown exception, if any
     *
     * @param \Exception $exception The exception instance to inject
     *
     * @return null
     */
    public function injectThrownException(\Exception $exception);

    /**
     * Getter method for property $isAbstract
     *
     * @return boolean
     */
    public function isAbstract();

    /**
     * Getter method for property $isFinal
     *
     * @return boolean
     */
    public function isFinal();

    /**
     * Getter method for property $isStatic
     *
     * @return boolean
     */
    public function isStatic();

    /**
     * Will begin the execution of the initially invoked method.
     * Acts as a wrapper around the initial method logic and will return the same result and throw the same exceptions,
     * so use it instead of the original call
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function proceed();

    /**
     * Setter method for property $parameters
     *
     * @param array $parameters New parameters to set
     *
     * @return null
     */
    public function setParameters(array $parameters);
}
