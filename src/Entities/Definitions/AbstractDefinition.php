<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Definitions\AbstractDefinition
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

namespace AppserverIo\Doppelgaenger\Entities\Definitions;

/**
 * This class is a combining parent class for all definition classes.
 * Just to give them a known parent
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
abstract class AbstractDefinition
{

    /**
     * List of lists of any ancestral invariants
     *
     * @var integer|boolean $endLine
     */
    protected $endLine = false;

    /**
     * List of lists of any ancestral invariants
     *
     * @var integer|boolean $startLine
     */
    protected $startLine = false;

    /**
     * Getter for the end line of this structure.
     * FALSE if unknown
     *
     * @return integer|boolean
     */
    public function getEndLine()
    {
        return $this->endLine;
    }

    /**
     * Getter for the start line of this structure.
     * FALSE if unknown
     *
     * @return integer|boolean
     */
    public function getStartLine()
    {
        return $this->startLine;
    }

    /**
     * Setter for the end line of this structure
     *
     * @param integer|boolean $endLine The line the structure ends in
     *
     * @return void
     */
    public function setEndLine($endLine)
    {
        $this->endLine = $endLine;
    }

    /**
     * Setter for the start line of this structure
     *
     * @param integer|boolean $startLine The line the structure starts in
     *
     * @return void
     */
    public function setStartLine($startLine)
    {
        $this->startLine = $startLine;
    }
}
