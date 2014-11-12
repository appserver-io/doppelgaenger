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
 * @package    AppserverIo_Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Entities\Pointcuts;

/**
 * AppserverIo\Doppelgaenger\Entities\Pointcuts\ExecutePointcut
 *
 * Pointcut which
 *
 * @category   Appserver
 * @package    AppserverIo_Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class ExecutePointcut extends AbstractSignaturePointcut
{

    /**
     * Whether or not the pointcut is considered static, meaning is has to be weaved and evaluated during runtime
     * anyway
     *
     * @var boolean IS_STATIC
     */
    const IS_STATIC = true;

    /**
     * The type of this pointcut
     *
     * @var string TYPE
     */
    const TYPE = 'execute';

    /**
     * Returns a string representing a boolean condition which can be used to determine if
     * the pointcut has to be executed
     *
     * @return string
     */
    public function getConditionString()
    {
        // if negated we have to prepare different matters of comparison
        $comparator = '!==';
        if ($this->isNegated()) {

            $comparator = '===';
        }

        // build up code searching within the methods backtrace
        $code = 'count(array_filter(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), function(&$value) {;
            $expression = ' . $this->getExpression() . ';
            foreach ($value as $entry) {
                $caller = @$entry["class"] . @$entry["type"] . @$entry["function"];

                if (preg_match("/" . $expression . "/", $caller) === 1) {

                    return true;
                }
            }
            })) ' . $comparator .' 0';

        return $code;
    }

    /**
     * Returns a string representation the actual execution of the pointcut logic
     *
     * @return string
     */
    public function getExecutionString()
    {
        return '';
    }

    /**
     * Whether or not the pointcut matches a given candidate.
     * Will always return true as execute pointcuts can only be evaluated during runtime
     *
     * @param mixed $candidate
     *
     * @return boolean
     */
    public function matches($candidate)
    {
        return true;
    }
}