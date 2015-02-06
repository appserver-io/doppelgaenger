<?php

/**
 * \AppserverIo\Doppelgaenger\Exceptions\ExceptionFactory
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

namespace AppserverIo\Doppelgaenger\Exceptions;

/**
 * Factory to get the right exception object (or class name) for the right occasion.
 * This was implemented to enable custom exception mapping
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class ExceptionFactory
{
    /**
     * Will return the name of the exception class for the needed error type
     *
     * @param string $type The type of exception we need
     *
     * @return string
     */
    public function getClassName($type)
    {
        return $this->getName($type);
    }

    /**
     * Will return an instance of the exception fitting the error type we specified
     *
     * @param string $type   The type of exception we need
     * @param array  $params Parameter array we will pass to the exception's constructor
     *
     * @return \Exception
     */
    public function getInstance($type, $params = array())
    {
        $name = $this->getName($type);

        return call_user_func_array(array($name, '__construct'), $params);
    }

    /**
     * Will return the name of the Exception class as it is mapped to a certain error type
     *
     * @param string $type The type of exception we need
     *
     * @return string
     */
    private function getName($type)
    {
        // What kind of exception do we need?
        switch ($type) {
            case 'precondition':

                $name = 'AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPreconditionException';
                break;

            case 'postcondition':

                $name = 'AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPostconditionException';
                break;

            case 'invariant':

                $name = 'AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenInvariantException';
                break;

            default:

                $name = $type;
                break;
        }

        // If we got an exception from this namespace, return it's full name
        if (class_exists(__NAMESPACE__ . '\\' . $name)) {
            return __NAMESPACE__ . '\\' . $name;

        } elseif (class_exists('\\' . $name)) {
            // If we got an exception class from another namespace we will return this one

            return $name;

        } else {
            // Otherwise we will return the most basic thing

            return 'Exception';
        }

    }
}
