<?php
/**
 * File containing the StructureParserFactory class
 *
 * PHP version 5
 *
 * @category   Doppelgaenger
 * @package    AppserverIo\Doppelgaenger
 * @subpackage Parser
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
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
 * @category   Php-by-contract
 * @package    AppserverIo\Doppelgaenger
 * @subpackage Parser
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
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
        $class = __NAMESPACE__ . '\\' . ucfirst($type) . 'Parser';

        if (!class_exists($class)) {

            throw new ParserException('Unknown parser type ' . $type);
        }

        return $class;
    }
}
