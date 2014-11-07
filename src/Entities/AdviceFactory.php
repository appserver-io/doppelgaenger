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
 * @category   Appserver
 * @package    AppserverIo_Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Entities;

/**
 * AppserverIo\Doppelgaenger\Entities\AdviceFactory
 *
 * <TODO CLASS DESCRIPTION>
 *
 * @category   Appserver
 * @package    AppserverIo_Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class AdviceFactory
{
    /**
     * Will return an instance of the exception fitting the error type we specified
     *
     * @param string $name   The type of exception we need
     * @param array  $params Parameter array we will pass to the exception's constructor
     *
     * @return \Exception
     *
     * @throws \InvalidArgumentException
     */
    public function getInstance($name, array $params = array())
    {
        // if we do not know this advice we will throw an exception
        if (!class_exists()) {

            throw new \InvalidArgumentException('We do not support advices ');
        }

        return call_user_func_array($name->__construct(), $params);
    }
}

 