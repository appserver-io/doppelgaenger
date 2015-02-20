<?php

/**
 * \AppserverIo\Doppelgaenger\Tests\TestExceptionLogger
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

namespace AppserverIo\Doppelgaenger\Tests;

use Psr\Log\LoggerInterface;

/**
 * Logger which throws a \AppserverIo\Doppelgaenger\Tests\TestLoggerUsedException instead of a log message
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class TestExceptionLogger implements LoggerInterface
{
    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return null
     *
     * @throws \AppserverIo\Doppelgaenger\Tests\TestLoggerUsedException
     */
    public function emergency($message, array $context = array())
    {
        throw new TestLoggerUsedException($message);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return null
     *
     * @throws \AppserverIo\Doppelgaenger\Tests\TestLoggerUsedException
     */
    public function alert($message, array $context = array())
    {
        throw new TestLoggerUsedException($message);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return null
     *
     * @throws \AppserverIo\Doppelgaenger\Tests\TestLoggerUsedException
     */
    public function critical($message, array $context = array())
    {
        throw new TestLoggerUsedException($message);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
     *
     * @throws \AppserverIo\Doppelgaenger\Tests\TestLoggerUsedException
     */
    public function error($message, array $context = array())
    {
        throw new TestLoggerUsedException($message);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return null
     *
     * @throws \AppserverIo\Doppelgaenger\Tests\TestLoggerUsedException
     */
    public function warning($message, array $context = array())
    {
        throw new TestLoggerUsedException($message);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return null
     *
     * @throws \AppserverIo\Doppelgaenger\Tests\TestLoggerUsedException
     */
    public function notice($message, array $context = array())
    {
        throw new TestLoggerUsedException($message);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return null
     *
     * @throws \AppserverIo\Doppelgaenger\Tests\TestLoggerUsedException
     */
    public function info($message, array $context = array())
    {
        throw new TestLoggerUsedException($message);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return null
     *
     * @throws \AppserverIo\Doppelgaenger\Tests\TestLoggerUsedException
     */
    public function debug($message, array $context = array())
    {
        throw new TestLoggerUsedException($message);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     *
     * @throws \AppserverIo\Doppelgaenger\Tests\TestLoggerUsedException
     */
    public function log($level, $message, array $context = array())
    {
        throw new TestLoggerUsedException($message);
    }
}
