<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Assertions\RawAssertion
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

namespace AppserverIo\Doppelgaenger\Entities\Assertions;

/**
 * This class provides a way of using php syntax assertions
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class RawAssertion extends AbstractAssertion
{
    /**
     * @var string $content Php code string we want to execute as an assertion
     */
    public $content;

    /**
     * Default constructor
     *
     * @param string $content Php code string we want to execute as an assertion
     */
    public function __construct($content)
    {
        $this->content = $content;

        parent::__construct();
    }

    /**
     * Will return a string representation of this assertion
     *
     * @return string
     */
    public function getString()
    {
        return (string)$this->content;
    }

    /**
     * Invert the logical meaning of this assertion
     *
     * @return bool
     */
    public function invert()
    {
        if ($this->inverted === false) {
            $this->content = '!(' . $this->content . ')';
            $this->inverted = true;

            return true;

        } elseif ($this->inverted === true) {
            // Just unset the parts of $this->content we do not need
            unset($this->content[0]);
            unset($this->content[1]);
            unset($this->content[strlen($this->content) - 1]);

            $this->inverted = false;

            return true;

        } else {
            return false;
        }
    }
}
