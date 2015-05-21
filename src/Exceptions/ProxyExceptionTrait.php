<?php

/**
 * \AppserverIo\Doppelgaenger\Exceptions\ProxyExceptionTrait
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
 * @link      https://github.com/appserver-io-psr/mop
 * @link      http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\Exceptions;

/**
 * Abstract base exception which allows to state
 * a different throwing file and line than the original exception would
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-psr/mop
 * @link      http://www.appserver.io/
 */
trait ProxyExceptionTrait
{

    /**
     * Setter for the line the exception states it has been thrown/created at
     *
     * @param integer $line The file the exceptions seems to have happened in
     *
     * @return void
     */
    public function setLine($line)
    {
        $this->line = $line;
    }

    /**
     * Setter for the file the exception states it has been thrown in
     *
     * @param string $file The line in which the exception seems to have happened
     *
     * @return void
     */
    public function setFile($file)
    {
        $this->file = $file;
    }
}
