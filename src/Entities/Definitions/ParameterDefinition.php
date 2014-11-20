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
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\Entities\Definitions;

/**
 * AppserverIo\Doppelgaenger\Entities\Definitions\ParameterDefinition
 *
 * Allows us to keep track of a functions parameters
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class ParameterDefinition
{
    /**
     * @var string $type Type hint (if any)
     */
    public $type;

    /**
     * @var string $name Name of the parameter
     */
    public $name;

    /**
     * @var mixed $defaultValue The parameter's default value (if any)
     */
    public $defaultValue;

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->type = '';
        $this->name = '';
        $this->defaultValue = '';
    }

    /**
     * Will return a string representation of the defined parameter
     *
     * @param string $mode We can switch how the string should be structured.
     *                     Choose from "definition", "call"
     *
     * @return string
     */
    public function getString($mode = 'definition')
    {
        // Prepare the parts
        $stringParts = array();

        if ($mode === 'call') {

            // Get the name
            $stringParts[] = $this->name;

        } elseif ($mode === 'definition') {

            // Get the type
            $stringParts[] = $this->type;

            // Get the name
            $stringParts[] = $this->name;

            if ($this->defaultValue !== '') {

                // Get the default value
                $stringParts[] = '=';
                $stringParts[] = $this->defaultValue;
            }

        } else {

            return '';
        }

        return implode(' ', $stringParts);
    }
}
