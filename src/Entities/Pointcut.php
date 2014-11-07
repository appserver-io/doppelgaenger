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
 * @category   Appserver
 * @package    AppserverIo\Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Entities;

use AppserverIo\Doppelgaenger\Entities\Lists\AdviceList;
use AppserverIo\Doppelgaenger\Entities\Lists\JoinpointList;

/**
 * AppserverIo\Doppelgaenger\Entities\Pointcut
 *
 * Definition of a pointcut as a combination of a joinpoint and advices
 *
 * @category   Appserver
 * @package    AppserverIo\Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class Pointcut extends AbstractLockableEntity
{

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->advices = new AdviceList();
        $this->joinpoints = new JoinpointList();
        $this->string = '';
    }

    /**
     * Lists of advices for the mentioned joinpoint
     *
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\AdviceList $advices
     */
    protected $advices;

    /**
     * Joinpoints at which the enclosed advices have to be weaved
     *
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\JoinpointList $joinpoints
     */
    protected $joinpoints;

    /**
     * Original string definition of the pointcut
     *
     * @var string $string
     */
    protected $string;
}
