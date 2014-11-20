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

use AppserverIo\Doppelgaenger\Entities\Lists\StructureDefinitionList;

/**
 * AppserverIo\Doppelgaenger\Entities\Definitions\FileDefinition
 *
 * Provides a definition of a file (containing a PHP structure)
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class FileDefinition
{
    /**
     * @var string $path Path to the file
     */
    public $path;

    /**
     * @var string $name Name of the file including extension
     */
    public $name;

    /**
     * @var string $namespace Namespace of the enclosed structure (if any)
     */
    public $namespace;

    /**
     * @var array $usedNamespaces Qualified structure names referenced by use statements within the file
     */
    public $usedNamespaces;

    /**
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\StructureDefinitionList $structureDefinitions
     *          List of structures in the file
     */
    public $structureDefinitions;

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->path = '';
        $this->name = '';
        $this->namespace = '';
        $this->usedNamespaces = array();
        $this->structureDefinitions = new StructureDefinitionList();
    }
}
