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
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Entities\Pointcuts;

use AppserverIo\Doppelgaenger\Entities\AbstractLockableEntity;
use AppserverIo\Doppelgaenger\Interfaces\Pointcut;

/**
 * AppserverIo\Doppelgaenger\Entities\Pointcut\AbstractPointcut
 *
 * Definition of a pointcut as a combination of a joinpoint and advices
 *
 * @category   Appserver
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 *
 * @see        https://www.eclipse.org/aspectj/doc/next/progguide/quick.html
 * @see        https://www.eclipse.org/aspectj/doc/next/progguide/semantics-pointcuts.html
 */
abstract class AbstractPointcut extends AbstractLockableEntity implements Pointcut
{

    /**
     * Raw expression as defined within code
     *
     * @var string $expression
     */
    protected $expression;

    /**
     * Has the result of any match to be negated?
     *
     * @var boolean $isNegated
     */
    protected $isNegated;

    /**
     * Default constructor
     *
     * @param string  $expression String representing the expression defining this pointcut
     * @param boolean $isNegated  If any match made against this pointcut's expression has to be negated in its result
     */
    public function __construct($expression, $isNegated)
    {
        $this->expression = $expression;
        $this->isNegated = $isNegated;
    }

    /**
     * Getter for the expression property
     *
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * Returns the pattern which is used to match and define this pointcut
     *
     * @return string
     *
     * @Enum({"Signature", "TypePattern", "Expression", "Type", "Pointcut"})
     */
    public function getMatchPattern()
    {
        return static::MATCH_PATTERN;
    }

    /**
     * Getter for the type property
     *
     * @return string
     */
    public function getType()
    {
        return static::TYPE;
    }

    /**
     * Whether or not the pointcut is considered static, meaning is has to be weaved and evaluated during runtime
     * anyway
     *
     * @return boolean
     */
    public function isStatic()
    {
        return static::IS_STATIC;
    }

    /**
     * Whether or not the pointcut match has to be negated in its result
     *
     * @return boolean
     */
    public function isNegated()
    {
        return $this->isNegated;
    }
}
