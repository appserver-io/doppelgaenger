<?php

/**
 * \AppserverIo\Doppelgaenger\Dictionaries\Annotations
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

namespace AppserverIo\Doppelgaenger\Dictionaries;

/**
 * Contains keywords used as annotations
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class Annotations
{
    /**
     * The keyword for annotations defining invariants
     *
     * @var string INVARIANT
     */
    const INVARIANT = '@invariant';

    /**
     * The keyword for annotations defining postconditions
     *
     * @var string POSTCONDITION
     */
    const POSTCONDITION = '@ensures';

    /**
     * The keyword for annotations defining preconditions
     *
     * @var string PRECONDITION
     */
    const PRECONDITION = '@requires';
}
