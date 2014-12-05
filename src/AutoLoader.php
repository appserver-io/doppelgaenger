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

// Load the constants if not already done
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Constants.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Dictionaries' . DIRECTORY_SEPARATOR . 'Placeholders.php';

/**
 * AppserverIo\Doppelgaenger\AutoLoader
 *
 * Will provide autoloader functionality as an entry point for parsing and code generation
 *
 * @category  Library
 * @package   Doppelgaenger
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2014 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.appserver.io/
 */
class AutoLoader
{
    /**
     * The register for any known aspects
     *
     * @var \AppserverIo\Doppelgaenger\AspectRegister $aspectRegister
     */
    protected $aspectRegister;

    /**
     * @var \AppserverIo\Doppelgaenger\Config $config The configuration we base our actions on
     */
    protected $config;

    /**
     * @var \AppserverIo\Doppelgaenger\CacheMap $cache Cache map to keep track of already processed files
     */
    protected $cache;

    /**
     * @var \AppserverIo\Doppelgaenger\Generator $generator Generator instance if we need to create a new definition
     */
    protected $generator;

    /**
     * In some cases the autoloader instance is not thrown away, saving the structure map might be a benefit here
     *
     * @var \AppserverIo\Doppelgaenger\StructureMap $structureMap
     */
    protected $structureMap;

    /**
     * @const string OUR_LOADER Name of our class loading method as we will register it
     */
    const OUR_LOADER = 'loadClass';

    /**
     * Default constructor
     *
     * @param \AppserverIo\Doppelgaenger\Config|null $config An already existing config instance
     */
    public function __construct(Config $config = null)
    {
        // If we got a config we can use it, if not we will get a context less config instance
        if (is_null($config)) {

            $this->config = new Config();

        } else {

            $this->config = $config;
        }

        // Now that we got the config we can create a structure map to load from
        $this->structureMap = new StructureMap(
            $this->config->getValue('autoloader/dirs'),
            $this->config->getValue('enforcement/dirs'),
            $this->config
        );

        $this->cache = null;
        $this->aspectRegister = new AspectRegister();
    }

    /**
     * Getter for the $aspectRegister property
     *
     * @return \AppserverIo\Doppelgaenger\AspectRegister
     */
    public function getAspectRegister()
    {
        return $this->aspectRegister;
    }

    /**
     * Getter for the config member
     *
     * @return \AppserverIo\Doppelgaenger\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Getter for the structureMap member
     *
     * @return \AppserverIo\Doppelgaenger\StructureMap
     */
    public function getStructureMap()
    {
        return $this->structureMap;
    }

    /**
     * Will inject an AspectRegister instance into the generator
     *
     * @param \AppserverIo\Doppelgaenger\AspectRegister $aspectRegister The AspectRegister instance to inject
     *
     * @return null
     */
    public function injectAspectRegister(AspectRegister $aspectRegister)
    {
        $this->aspectRegister = $aspectRegister;
    }

    /**
     * Will load any given structure based on it's availability in our structure map which depends on the configured
     * project directories.
     * If the structure cannot be found we will redirect to the composer autoloader which we registered as a fallback
     *
     * @param string $className The name of the structure we will try to load
     *
     * @return boolean
     */
    public function loadClass($className)
    {

        // Might the class be a omitted one? If so we can require the original.
        if ($this->config->hasValue('autoloader/omit')) {

            $omittedNamespaces = $this->config->getValue('autoloader/omit');
            foreach ($omittedNamespaces as $omitted) {

                // If our class name begins with the omitted part e.g. it's namespace
                if (strpos($className, str_replace('\\\\', '\\', $omitted)) === 0) {

                    return false;
                }
            }
        }

        // Do we have the file in our cache dir? If we are in development mode we have to ignore this.
        if ($this->config->getValue('environment') !== 'development') {

            $cachePath = $this->config->getValue('cache/dir') . DIRECTORY_SEPARATOR . str_replace('\\', '_', $className) . '.php';

            if (is_readable($cachePath)) {

                $res = fopen($cachePath, 'r');
                $str = fread($res, 384);

                $success = preg_match(
                    '/' . Dictionaries\Placeholders::ORIGINAL_PATH_HINT . '(.+)' .
                    Dictionaries\Placeholders::ORIGINAL_PATH_HINT . '/',
                    $str,
                    $tmp
                );

                if ($success > 0) {

                    $tmp = explode('#', $tmp[1]);

                    $path = $tmp[0];
                    $mTime = $tmp[1];

                    if (filemtime($path) == $mTime) {

                        require $cachePath;
                        return true;
                    }
                }
            }
        }

        // If we are loading something of our own library we can skip to composer
        if ((strpos($className, 'AppserverIo\Doppelgaenger') === 0 && strpos($className, 'AppserverIo\Doppelgaenger\Tests') === false) ||
            strpos($className, 'PHP') === 0
        ) {

            return false;
        }

        // If the structure map did not get filled by now we will do so here
        if ($this->structureMap->isEmpty()) {

            $this->structureMap->fill();
        }

        // Get the file from the map
        $file = $this->structureMap->getEntry($className);

        // Did we get something? If not return false.
        if ($file === false) {

            return false;
        }

        // We are still here, so we know the class and it is not omitted. Does it contain annotations then?
        if (!$file->hasAnnotations() || !$file->isEnforced()) {

            require $file->getPath();

            return true;
        }

        // So we have to create a new class definition for this original class.
        // Get a current cache instance if we do not have one already.
        if ($this->cache === null) {

            // We also require the classes of our maps as we do not have proper autoloading in place
            $this->cache = new CacheMap($this->getConfig()->getValue('cache/dir'), array(), $this->config);
        }
        $this->generator = new Generator($this->structureMap, $this->cache, $this->config, $this->aspectRegister);

        // Create the new class definition
        if ($this->generator->create($file, $this->config->getValue('enforcement/contract-inheritance')) === true) {

            // Require the new class, it should have been created now
            $file = $this->generator->getFileName($className);

            if ($file !== false && is_readable($file) === true) {

                require $file;

                return true;
            }

        } else {

            return false;
        }

        // Still here? That sounds like bad news!
        return false;
    }

    /**
     * Will register our autoloading method at the beginning of the spl autoloader stack
     *
     * @param boolean $throw   Should we throw an exception on error?
     * @param boolean $prepend If you want to NOT prepend you might, but you should not
     *
     * @return null
     */
    public function register($throw = true, $prepend = true)
    {
        // Now we have a config no matter what, we can store any instance we might need
        $this->config->storeInstances();

        // We want to let our autoloader be the first in line so we can react on loads
        // and create/return our contracted definitions.
        // So lets use the prepend parameter here.
        spl_autoload_register(array($this, self::OUR_LOADER), $throw, $prepend);
    }

    /**
     * Uninstalls this class loader from the SPL autoloader stack.
     *
     * @return void
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, self::OUR_LOADER));
    }
}
