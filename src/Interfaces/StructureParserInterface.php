<?php

/**
 * \AppserverIo\Doppelgaenger\Interfaces\StructureParserInterface
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

namespace AppserverIo\Doppelgaenger\Interfaces;

use AppserverIo\Doppelgaenger\Entities\Definitions\FileDefinition;
use AppserverIo\Doppelgaenger\Entities\Lists\StructureDefinitionList;

/**
 * Interface which describes parsers for structures like classes, interfaces and traits.
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
interface StructureParserInterface extends ParserInterface
{

    /**
     * Will return a structure definition. If a name is gives method will search for this particular structure.
     *
     * @param null $structureName Name of a certain structure we are searching for
     * @param bool $getRecursive  Will recursively load all conditions of ancestral structures
     *
     * @return StructureDefinitionInterface The definition of a the searched structure
     */
    public function getDefinition($structureName = null, $getRecursive = true);

    /**
     * Will return a list of structures found in a certain file
     *
     * @param string         $file           The path of the file to search in
     * @param FileDefinition $fileDefinition Definition of the file to pick details from
     * @param bool           $getRecursive   Do we need our ancestral information?
     *
     * @return StructureDefinitionList
     */
    public function getDefinitionListFromFile($file, FileDefinition $fileDefinition, $getRecursive = true);

    /**
     * Will return the token representing the structure the parser is used for e.g. T_CLASS
     *
     * @return integer
     */
    public function getToken();
}
