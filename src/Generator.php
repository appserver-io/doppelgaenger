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
 * @category  Library
 * @package   Doppelgaenger
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2014 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger;

use AppserverIo\Doppelgaenger\CacheMap;
use AppserverIo\Doppelgaenger\Entities\Annotations\Aspect as AspectAnnotation;
use AppserverIo\Doppelgaenger\Entities\Definitions\AspectDefinition;
use AppserverIo\Doppelgaenger\StructureMap;
use AppserverIo\Doppelgaenger\Exceptions\GeneratorException;
use AppserverIo\Doppelgaenger\Entities\Definitions\ClassDefinition;
use AppserverIo\Doppelgaenger\Entities\Definitions\InterfaceDefinition;
use AppserverIo\Doppelgaenger\Entities\Definitions\StructureDefinitionHierarchy;
use AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface;
use AppserverIo\Doppelgaenger\Entities\Definitions\Structure;
use AppserverIo\Doppelgaenger\Parser\StructureParserFactory;
use AppserverIo\Doppelgaenger\Config;
use AppserverIo\Doppelgaenger\Dictionaries\Placeholders;

/**
 * AppserverIo\Doppelgaenger\Generator
 *
 * This class initiates the creation of enforced structure definitions.
 *
 * @category  Library
 * @package   Doppelgaenger
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2014 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.appserver.io/
 */
class Generator
{

    /**
     * The register for any known aspects
     *
     * @var \AppserverIo\Doppelgaenger\AspectRegister $aspectRegister
     */
    protected $aspectRegister;

    /**
     * @var \AppserverIo\Doppelgaenger\CacheMap $cacheMap A cacheMap instance to organize our cache
     */
    protected $cacheMap;

    /**
     * @var \AppserverIo\Doppelgaenger\StructureMap $structureMap A structureMap instance to organize the known structures
     */
    protected $structureMap;

    /**
     * @var array $config The aspect of the configuration we need
     */
    protected $config;

    /**
     * Collection of definitions and their inheritance relation to each other
     *
     * @var \AppserverIo\Doppelgaenger\Entities\Definitions\StructureDefinitionHierarchy $structureDefinitionHierarchy
     */
    protected $structureDefinitionHierarchy;

    /**
     * Default constructor
     *
     * @param \AppserverIo\Doppelgaenger\StructureMap   $structureMap   A structureMap instance to organize the known structures
     * @param \AppserverIo\Doppelgaenger\CacheMap       $cache          A cacheMap instance to organize our cache
     * @param \AppserverIo\Doppelgaenger\Config         $config         Configuration
     * @param \AppserverIo\Doppelgaenger\AspectRegister $aspectRegister The register for known aspects
     */
    public function __construct(StructureMap $structureMap, CacheMap $cache, Config $config, AspectRegister $aspectRegister)
    {
        $this->cache = $cache;

        $this->structureMap = $structureMap;

        $this->config = $config;

        $this->aspectRegister = $aspectRegister;

        $this->structureDefinitionHierarchy = new StructureDefinitionHierarchy();
    }

    /**
     * Will create an altered definition of the structure defined in the $mapEntry variable.
     * Will also add it to the cache map
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Definitions\Structure $mapEntry        Entry of a StructureMap we want created
     * @param boolean                                                   $createRecursive If contract inheritance is enabled
     *
     * @throws \AppserverIo\Doppelgaenger\Exceptions\GeneratorException
     *
     * @return boolean
     */
    public function create(Structure $mapEntry, $createRecursive = false)
    {
        // We know what we are searching for and we got a fine factory so lets get us a parser
        $structureParserFactory = new StructureParserFactory();
        $parser = $structureParserFactory->getInstance(
            $mapEntry->getType(),
            $mapEntry->getPath(),
            $this->config,
            $this->structureMap,
            $this->structureDefinitionHierarchy
        );

        // Lets get the definition we are looking for
        $structureDefinition = $parser->getDefinition($mapEntry->getIdentifier(), $createRecursive);

        if (!$structureDefinition instanceof StructureDefinitionInterface) {

            return false;
        }

        $qualifiedName = $structureDefinition->getQualifiedName();
        $filePath = $this->createFilePath(
            $qualifiedName,
            $mapEntry->getPath()
        );

        $tmp = $this->createFileFromDefinition($filePath, $structureDefinition);

        if ($tmp === false) {

            throw new GeneratorException('Could not create altered definition for ' . $qualifiedName);
        }
        // Now get our new file into the cacheMap
        $this->cache->add(
            new Structure(
                filectime($mapEntry->getPath()),
                $qualifiedName,
                $filePath,
                $structureDefinition->getType()
            )
        );

        // Still here? Than everything worked out great.
        return true;
    }

    /**
     * Will return the path the cached and altered definition will have
     *
     * @param string $structureName Name of the structure we want to update
     *
     * @return string
     *
     * TODO implement this somewhere more accessible, others might need it too (e.g. autoloader)
     */
    protected function createFilePath($structureName)
    {
        // s a file can contain multiple structures we will substitute the filename with the structure name
        $tmpFileName = ltrim(str_replace('\\', '_', $structureName), '_');

        return $this->config->getValue('cache/dir') . DIRECTORY_SEPARATOR . $tmpFileName . '.php';
    }

    /**
     * Will create a file containing the altered definition
     *
     * @param string                                                             $targetFileName      The intended name of the
     *                                                                                       new file
     * @param \AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface $structureDefinition The definition of the
     *                                                                                       structure we will alter
     *
     * @throws \InvalidArgumentException
     *
     * @return boolean
     */
    protected function createFileFromDefinition(
        $targetFileName,
        StructureDefinitionInterface $structureDefinition
    ) {
        // We have to check which structure type we got
        $definitionType = get_class($structureDefinition);

        // Call the method accordingly
        $tmp = explode('\\', $definitionType);
        $creationMethod = 'createFileFrom' . array_pop($tmp);

        // Check if we got something, if not we will default to class
        if (!method_exists($this, $creationMethod)) {

            $creationMethod = 'createFileFromClassDefinition';
        }

        return $this->$creationMethod($targetFileName, $structureDefinition);
    }

    /**
     * Will create a file with the altered class definition as its content.
     * We will register the aspect first
     *
     * @param string                                                           $targetFileName   The intended name of the new file
     * @param \AppserverIo\Doppelgaenger\Entities\Definitions\AspectDefinition $aspectDefinition The definition of the structure we will alter
     *
     * @return boolean
     */
    protected function createFileFromAspectDefinition($targetFileName, AspectDefinition $aspectDefinition)
    {

        // register the aspect in our central aspect register
        $this->aspectRegister->register($aspectDefinition);

        // create the new definition
        return $this->createFileFromClassDefinition($targetFileName, $aspectDefinition);
    }

    /**
     * Will create a file for a given interface definition.
     * We will just copy the file here until the autoloader got refactored.
     *
     * @param string                                                              $targetFileName      The intended name of the new file
     * @param \AppserverIo\Doppelgaenger\Entities\Definitions\InterfaceDefinition $structureDefinition The definition of the structure we will alter
     *
     * @return boolean
     *
     * TODO remove when autoloader is able to recognize and skip interfaces
     */
    protected function createFileFromInterfaceDefinition(
        $targetFileName,
        InterfaceDefinition $structureDefinition
    ) {
        // Get the content of the file
        $content = file_get_contents($structureDefinition->getPath());

        // Make the one change we need, the original file path and modification timestamp
        $content = str_replace(
            '<?php',
            '<?php /* ' . Placeholders::ORIGINAL_PATH_HINT . $structureDefinition->getPath() . '#' .
            filemtime(
                $structureDefinition->getPath()
            ) . Placeholders::ORIGINAL_PATH_HINT . ' */',
            $content
        );

        return (boolean)file_put_contents($targetFileName, $content);
    }

    /**
     * Will create a file with the altered class definition as its content
     *
     * @param string                                                          $targetFileName      The intended name of the new file
     * @param \AppserverIo\Doppelgaenger\Entities\Definitions\ClassDefinition $structureDefinition The definition of the structure we will alter
     *
     * @return boolean
     */
    protected function createFileFromClassDefinition(
        $targetFileName,
        ClassDefinition $structureDefinition
    ) {
        // TODO, do we really use the filters only once?
        $res = fopen(
            $this->createFilePath($structureDefinition->getQualifiedName()),
            'w+'
        );

        // Append all configured filters but collect them for easier handling
        $appendedFilters = $this->appendDefaultFilters($res, $structureDefinition);

        // TODO remove this after testing
        $appendedFilters['IntroductionFilter'] = $this->appendFilter($res, 'AppserverIo\Doppelgaenger\StreamFilters\IntroductionFilter', $structureDefinition->getIntroductions());
        $appendedFilters['AdviceFilter'] = $this->appendFilter(
            $res,
            'AppserverIo\Doppelgaenger\StreamFilters\AdviceFilter',
            array('functionDefinitions' => $structureDefinition->getFunctionDefinitions(), 'aspectRegister' => $this->aspectRegister)
        );
        $appendedFilters['ProcessingFilter'] = $this->appendFilter($res, 'AppserverIo\Doppelgaenger\StreamFilters\ProcessingFilter', $structureDefinition->getFunctionDefinitions());

        $tmp = fwrite(
            $res,
            file_get_contents($structureDefinition->getPath(), time())
        );

        // Did we write something?
        if ($tmp > 0) {

            fclose($res);

            return true;

        } else {

            // Delete the empty file stub we made
            unlink(
                $this->createFilePath(
                    $structureDefinition->getQualifiedName()
                ),
                $res
            );

            fclose($res);

            return false;
        }
    }

    /**
     * Will append all needed filters based on the enforcement level stated in the configuration file.
     *
     * @param resource                                                           $res                 The resource we will append the filters to
     * @param \AppserverIo\Doppelgaenger\Interfaces\StructureDefinitionInterface $structureDefinition Structure definition providing needed information
     *
     * @return array
     */
    protected function appendDefaultFilters(
        & $res,
        StructureDefinitionInterface $structureDefinition
    ) {
        // resulting array with resources of appended filters
        $filters = array();

        // Lets get the enforcement level
        $levelArray = array();
        if ($this->config->hasValue('enforcement/level')) {

            $levelArray = array_reverse(str_split(decbin($this->config->getValue('enforcement/level'))));
        }

        // Whatever the enforcement level is, we will always need the skeleton filter.
        stream_filter_register('SkeletonFilter', 'AppserverIo\Doppelgaenger\StreamFilters\SkeletonFilter');
        $filters['SkeletonFilter'] = stream_filter_append(
            $res,
            'SkeletonFilter',
            STREAM_FILTER_WRITE,
            $structureDefinition
        );

        // Now lets register and append the filters if they are mapped to a 1
        // Lets have a look at the precondition filter first
        if (isset($levelArray[0]) && $levelArray[0] == 1) {

            // Do we even got any preconditions?
            $filterNeeded = false;
            $iterator = $structureDefinition->getFunctionDefinitions()->getIterator();
            foreach ($iterator as $functionDefinition) {

                if ($functionDefinition->getAllPreconditions()->count() !== 0) {

                    $filterNeeded = true;
                    break;
                }
            }

            if ($filterNeeded) {

                stream_filter_register('PreconditionFilter', 'AppserverIo\Doppelgaenger\StreamFilters\PreconditionFilter');
                $filters['PreconditionFilter'] = stream_filter_append(
                    $res,
                    'PreconditionFilter',
                    STREAM_FILTER_WRITE,
                    $structureDefinition->getFunctionDefinitions()
                );
            }
        }

        // What about the postcondition filter?
        if (isset($levelArray[1]) && $levelArray[1] == 1) {

            // Do we even got any postconditions?
            $filterNeeded = false;
            $iterator = $structureDefinition->getFunctionDefinitions()->getIterator();
            foreach ($iterator as $functionDefinition) {

                if ($functionDefinition->getAllPostconditions()->count() !== 0) {

                    $filterNeeded = true;
                    break;
                }
            }

            if ($filterNeeded) {

                stream_filter_register('PostconditionFilter', 'AppserverIo\Doppelgaenger\StreamFilters\PostconditionFilter');
                $filters['PostconditionFilter'] = stream_filter_append(
                    $res,
                    'PostconditionFilter',
                    STREAM_FILTER_WRITE,
                    $structureDefinition->getFunctionDefinitions()
                );
            }
        }

        // What about the invariant filter?
        if (isset($levelArray[2]) && $levelArray[2] == 1) {

            // Do we even got any invariants?
            if ($structureDefinition->getInvariants()->count(true) !== 0) {

                stream_filter_register('InvariantFilter', 'AppserverIo\Doppelgaenger\StreamFilters\InvariantFilter');
                $filters['InvariantFilter'] = stream_filter_append($res, 'InvariantFilter', STREAM_FILTER_WRITE, $structureDefinition);
            }
        }

        // We ALWAYS need the processing filter. Everything else would not make any sense
        stream_filter_register('EnforcementFilter', 'AppserverIo\Doppelgaenger\StreamFilters\EnforcementFilter');
        $filters['EnforcementFilter'] = stream_filter_append($res, 'EnforcementFilter', STREAM_FILTER_WRITE, $this->config);

        return $filters;
    }

    /**
     * Will append a given filter to a resource.
     * Might fail if the filter cannot be found.
     * Will return true if filter got appended successfully
     *
     * @param resource $res         The resource we will append the filters to
     * @param string   $filterClass The fully qualified name of the filter class
     * @param mixed    $params      Whatever params the filter might need
     *
     * @return resource
     *
     * @throws \AppserverIo\Doppelgaenger\Exceptions\GeneratorException
     */
    public function appendFilter(& $res, $filterClass, $params)
    {
        // check if the given filter exists and throw an exception if not
        if (!class_exists($filterClass)) {

            throw new GeneratorException('Could not find filter class ' . $filterClass);
        }

        // append the filter to the given resource
        $filterName = substr(strrchr($filterClass, '\\'), 1);
        stream_filter_register($filterName, $filterClass);
        return stream_filter_append(
            $res,
            $filterName,
            STREAM_FILTER_WRITE,
            $params
        );
    }

    /**
     * Return the cache path (as organized by our cache map) for a given structure name
     *
     * @param string $structureName The structure we want the cache path for
     *
     * @return boolean|string
     */
    public function getFileName($structureName)
    {
        $mapEntry = $this->cache->getEntry($structureName);

        if (!$mapEntry instanceof Structure) {

            return false;
        }

        return $mapEntry->getPath();
    }

    /**
     * Method used to update certain structures
     *
     * @param string $structureName Name of the structure we want to update
     *
     * @return boolean
     */
    public function update($structureName)
    {
        return $this->create($structureName, true);
    }
}
