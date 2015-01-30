<?php

/**
 * \AppserverIo\Doppelgaenger\Interfaces\MapInterface
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

use AppserverIo\Doppelgaenger\Entities\Definitions\Structure;

/**
 * An interface defining the functionality of any possible map class
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
interface MapInterface
{
    /**
     * Will return all entries within a map. If needed only entries of contracted
     * structures will be returned.
     *
     * @param boolean $contracted Do we only want entries containing contracts?
     *
     * @return mixed
     */
    public function getEntries($contracted = false);

    /**
     * Will add a structure entry to the map.
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Definitions\Structure $structure The structure to add
     *
     * @return bool
     */
    public function add(Structure $structure);

    /**
     * Do we have an entry for the given identifier
     *
     * @param string $identifier The identifier of the entry we try to find
     *
     * @return bool
     */
    public function entryExists($identifier);

    /**
     * Will update a given structure.
     * If the entry does not exist we will create it
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Definitions\Structure $structure The structure to update
     *
     * @return void
     *
     * TODO implement this in the implementing classes
     */
    public function update(Structure $structure = null);

    /**
     * Will return the entry specified by it's identifier.
     * If none is found, false will be returned.
     *
     * @param string $identifier The identifier of the entry we try to find
     *
     * @return boolean|\AppserverIo\Doppelgaenger\Entities\Definitions\Structure
     */
    public function getEntry($identifier);

    /**
     * Checks if the entry for a certain structure is recent if one was specified.
     * If not it will check if the whole map is recent.
     *
     * @param null|string $identifier The identifier of the entry we try to find
     *
     * @return  boolean
     */
    public function isRecent($identifier = null);

    /**
     * Will return an array of all entry identifiers which are stored in this map.
     * We might filter by entry type
     *
     * @param string|null $type The type to filter by
     *
     * @return array
     */
    public function getIdentifiers($type = null);

    /**
     * Will return an array of all files which are stored in this map.
     * Will include the full path if $fullPath is true.
     *
     * @param boolean $fullPath Do we need the full path?
     *
     * @return  array
     */
    public function getFiles($fullPath = true);

    /**
     * Removes an entry from the map of structures.
     *
     * @param null|string $identifier The identifier of the entry we try to find
     *
     * @return boolean
     */
    public function remove($identifier);
}
