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
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Entities;

/**
 * AppserverIo\Doppelgaenger\Entities\MethodInvocation
 *
 * DTO which will be used to represent an invoked method and will therefor hold information about it as well as the
 * functionality to invoke the initially called logic
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class MethodInvocation
{

    /**
     * Callback which allows to call the initially invoked
     *
     * @var callable $callback
     */
    protected $callback;

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
     * @var array $name
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
     * @param callable $callback      Callback which allows to call the initially invoked
     * @param object   $context       The context in which the invocation happens e.g. $this
     * @param boolean  $isAbstract    Is the function abstract?
     * @param boolean  $isFinal       Is the function final?
     * @param boolean  $isStatic      Is the method static?
     * @param string   $name          The name of the function
     * @param array    $parameters    Array of parameters of the form <PARAMETER_NAME> => <PARAMETER_VALUE>
     * @param string   $structureName Name of the structure (class/trait/...) which contains the method
     * @param string   $visibility    Visibility of the method
     */
    public function __construct(
        $callback,
        $context,
        $isAbstract,
        $isFinal,
        $isStatic,
        $name,
        array $parameters,
        $structureName,
        $visibility
    ) {
        $this->callback = $callback;
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
        try {

            $this->result = call_user_func_array($this->callback, $this->getParameters());

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
