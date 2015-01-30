<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\MethodInvocation
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

namespace AppserverIo\Doppelgaenger\Entities;

use AppserverIo\Doppelgaenger\Interfaces\MethodInvocationInterface;

/**
 * DTO which will be used to represent an invoked method and will therefor hold information about it as well as the
 * functionality to invoke the initially called logic
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class MethodInvocation implements MethodInvocationInterface
{

    /**
     * Array containing callbacks which allow to call the contained piece of code.
     * Allows for a chain of callbacks e.g. with advice chaining
     *
     * @var array<callable> $callbackChain
     */
    protected $callbackChain;

    /**
     * The context in which the invocation happens, is the same as accessing $this within the method logic
     *
     * @var object $context
     */
    protected $context;

    /**
     * Is the function abstract?
     *
     * @var boolean $isAbstract
     */
    protected $isAbstract;

    /**
     * Is the function final?
     *
     * @var boolean $isFinal
     */
    protected $isFinal;

    /**
     * Is the method static?
     *
     * @var boolean $isStatic
     */
    protected $isStatic;

    /**
     * The name of the function
     *
     * @var string $name
     */
    protected $name;

    /**
     * Array of parameters of the form <PARAMETER_NAME> => <PARAMETER_VALUE>
     *
     * @var array $parameters
     */
    protected $parameters;

    /**
     * The result of the method invocation
     *
     * @var mixed $result
     */
    protected $result;

    /**
     * Name of the structure (class/trait/...) which contains the method
     *
     * @var string $structureName
     */
    protected $structureName;

    /**
     * The exception thrown by the method invocation
     *
     * @var \Exception $thrownException
     */
    protected $thrownException;

    /**
     * Visibility of the method
     *
     * @var string $visibility
     */
    protected $visibility;

    /**
     * Default constructor
     *
     * @param array<callable> $callbackChain Callback which allows to call the initially invoked
     * @param object          $context       The context in which the invocation happens e.g. $this
     * @param boolean         $isAbstract    Is the function abstract?
     * @param boolean         $isFinal       Is the function final?
     * @param boolean         $isStatic      Is the method static?
     * @param string          $name          The name of the function
     * @param array           $parameters    Array of parameters of the form <PARAMETER_NAME> => <PARAMETER_VALUE>
     * @param string          $structureName Name of the structure (class/trait/...) which contains the method
     * @param string          $visibility    Visibility of the method
     */
    public function __construct(
        $callbackChain,
        $context,
        $isAbstract,
        $isFinal,
        $isStatic,
        $name,
        array $parameters,
        $structureName,
        $visibility
    ) {
        $this->callbackChain = $callbackChain;
        $this->context = $context;
        $this->isAbstract = $isAbstract;
        $this->isFinal = $isFinal;
        $this->isStatic = $isStatic;
        $this->name = $name;
        $this->parameters = $parameters;
        $this->structureName = $structureName;
        $this->visibility = $visibility;
    }

    /**
     * Getter method for property $context
     *
     * @return object
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Getter method for property $name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Getter for the result of the method invocation
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Getter method for property $parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Getter method for property $structureName
     *
     * @return string
     */
    public function getStructureName()
    {
        return $this->structureName;
    }

    /**
     * Getter method for property $thrownException
     *
     * @return string
     */
    public function getThrownException()
    {
        return $this->thrownException;
    }

    /**
     * Getter method for property $visibility
     *
     * @return string
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * Will be used to inject the result of the original method
     *
     * @param mixed $result The result to inject
     *
     * @return mixed
     */
    public function injectResult($result)
    {
        $this->result = $result;
    }

    /**
     * Used to injection the thrown exception, if any
     *
     * @param \Exception $thrownException The exception instance to inject
     *
     * @return null
     */
    public function injectThrownException(\Exception $thrownException)
    {
        $this->thrownException = $thrownException;
    }

    /**
     * Getter method for property $isAbstract
     *
     * @return boolean
     */
    public function isAbstract()
    {
        return $this->isAbstract;
    }

    /**
     * Getter method for property $isFinal
     *
     * @return boolean
     */
    public function isFinal()
    {
        return $this->isFinal;
    }

    /**
     * Getter method for property $isStatic
     *
     * @return boolean
     */
    public function isStatic()
    {
        return $this->isStatic;
    }

    /**
     * Will begin the execution of the initially invoked method.
     * Acts as a wrapper around the initial method logic and will return the same result and throw the same exceptions,
     * so use it instead of the original call
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function proceed()
    {
        // if the callback chain is empty we got a real problem, only thing we can do is trying to invoke the original
        // implementation.
        // but lets throw a warning so the user knows
        if (empty($this->callbackChain)) {
            trigger_error(
                'The callback chain for ' . $this->getStructureName() . '::' . $this->getName() . ' was empty, invoking original implementation.',
                E_USER_NOTICE
            );
            $this->callbackChain = array(
                array($this->getContext(), $this->getName())
            );
        }

        // get the first entry of the callback and remove it as we don't want to call methods twice
        $callback = reset($this->callbackChain);
        unset($this->callbackChain[key($this->callbackChain)]);

        try {
            // pass over the method invocation object (instead of original params) as long as we got something in the chain
            if (!empty($this->callbackChain)) {
                $this->result = call_user_func_array($callback, array($this));

            } else {
                $this->result = call_user_func_array($callback, $this->getParameters());
            }

        } catch (\Exception $e) {
            $this->thrownException = $e;
            throw $e;
        }

        return $this->result;
    }

    /**
     * Setter method for property $parameters
     *
     * @param array $parameters New parameters to set
     *
     * @return null
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }
}
