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
 * @subpackage Parser
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\Parser;

use AppserverIo\Doppelgaenger\Entities\Definitions\AspectDefinition;

/**
 * AppserverIo\Doppelgaenger\Parser\AspectParser
 *
 * <TODO CLASS DESCRIPTION>
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Parser
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class AspectParser extends ClassParser
{
    /**
     * Returns a ClassDefinition from a token array.
     *
     * This method will use a set of other methods to parse a token array and retrieve any
     * possible information from it. This information will be entered into a ClassDefinition object.
     *
     * @param array   $tokens       The token array containing structure tokens
     * @param boolean $getRecursive Do we have to get the ancestral conditions as well?
     *
     * @return \AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface
     */
    protected function getDefinitionFromTokens($tokens, $getRecursive = true)
    {
        // First of all we need a new AspectDefinition to fill
        $this->currentDefinition = new AspectDefinition();

        $this->currentDefinition = parent::getDefinitionFromTokens($tokens, $getRecursive);

        return $this->currentDefinition;
    }
}
