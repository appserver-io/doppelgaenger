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
 * @subpackage Traits
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\Traits;

use TechDivision\PersistenceContainerProtocol\Session;
use TechDivision\PersistenceContainerProtocol\RemoteObject;
use TechDivision\PersistenceContainerProtocol\RemoteMethod;
use TechDivision\PersistenceContainerProtocol\RemoteMethodCall;

/**
 * The proxy is used to create a new remote object of the
 * class with the requested name.
 *
 * namespace TechDivision\PersistenceContainerClient;
 *
 * use TechDivision\PersistenceContainerClient\ConnectionFactory;
 *
 * $connection = ConnectionFactory::createContextConnection();
 * $session = $connection->createContextSession();
 * $initialContext = $session->createInitialContext();
 *
 * $processor = $initialContext->lookup('Some\ProxyClass');
 *
 * @category  Doppelgaenger
 * @package   AppserverIo\Doppelgaenger
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_PersistenceContainerClient
 * @link      http://www.appserver.io
 */
trait RemoteProxyTrait
{

    /**
     * Holds the ContextSession for this proxy.
     *
     * @var \TechDivision\PersistenceContainerProtocol\Session
     */
    protected $session = null;

    /**
     * The name of the original object.
     *
     * @return string The name of the original object
     * @see \TechDivision\PersistenceContainerProtocol\RemoteObject::getClassName()
     */
    public function getClassName()
    {
        return __CLASS__;
    }

    /**
     * Sets the session with the connection instance.
     *
     * @param \TechDivision\PersistenceContainerProtocol\Session $session The session instance to use
     *
     * @return \TechDivision\PersistenceContainerProtocol\RemoteObject The instance itself
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     * Returns the session instance.
     *
     * @return \TechDivision\PersistenceContainerProtocol\Session The session instance
     * @see \TechDivision\PersistenceContainerProtocol\RemoteObject::getSession()
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Invokes the remote execution of the passed remote method.
     *
     * @param string $method The remote method to call
     * @param array  $params The parameters for the method call
     *
     * @return mixed The result of the remote method call
     */
    public function remoteCall($method, $params)
    {
        $methodCall = new RemoteMethodCall($this->getClassName(), $method, $this->getSession()->getSessionId());
        foreach ($params as $key => $value) {
            $methodCall->addParameter($key, $value);
        }
        return $this->__invoke($methodCall, $this->getSession());
    }
}
