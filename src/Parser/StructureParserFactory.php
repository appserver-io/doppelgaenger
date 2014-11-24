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

use AppserverIo\Doppelgaenger\Config;
use AppserverIo\Doppelgaenger\StructureMap;
use AppserverIo\Doppelgaenger\Entities\Definitions\StructureDefinitionHierarchy;
use AppserverIo\Doppelgaenger\Exceptions\ParserException;

/**
 * AppserverIo\Doppelgaenger\Parser\StructureParserFactory
 *
 * This class helps us getting the right parser for different structures
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Parser
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class StructureParserFactory
{
    /**
     * Will return the name of the parser class for the needed structure type
     *
     * @param string $type The type of exception we need
     *
     * @return string
     */
    public function getClassName($type)
    {
        return $this->getName($type);
    }

    /**
     * Will return an instance of the parser fitting the structure type we specified
     *
     * @param string                                                                       $type                         The
     *      structure type we need a parser for
     * @param string                                                                       $file                         The
     *      file we want to parse
     * @param \AppserverIo\Doppelgaenger\Config                                            $config                       Config
     * @param \AppserverIo\Doppelgaenger\StructureMap                                      $structureMap                 Struct-
     *      ure map to pass to the parser
     * @param \AppserverIo\Doppelgaenger\Entities\Definitions\StructureDefinitionHierarchy $structureDefinitionHierarchy The
     *      list of already parsed definitions from the structure's hierarchy
     *
     * @return mixed
     */
    public function getInstance(
        $type,
        $file,
        Config $config,
        StructureMap $structureMap,
        StructureDefinitionHierarchy & $structureDefinitionHierarchy
    ) {
        $name = $this->getName($type);

        return new $name($file, $config, $structureDefinitionHierarchy, $structureMap);
    }

    /**
     * Find the name of the parser class we need
     *
     * @param string $type The structure type we need a parser for
     *
     * @throws \AppserverIo\Doppelgaenger\Exceptions\ParserException
     *
     * @return string
     */
    protected function getName($type)
    {
        // What kind of exception do we need?
        $class = __NAMESPACE__ . '\\' . ucfirst(trim($type)) . 'Parser';
        error_log($class);
        error_log(var_export(class_exists($class)));
        if (!class_exists($class)) {

            throw new ParserException('Unknown parser type ' . $type);
        }

        return $class;
    }
}
