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

use AppserverIo\Doppelgaenger\Interfaces\Pointcut;

/**
 * AppserverIo\Doppelgaenger\Entities\Pointcuts\PointcutFactory
 *
 * Factory which will produce instances of specific pointcut classes based on their type and expression
 *
 * @category   Appserver
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class PointcutFactory
{
    /**
     * Will return an instance of an AsbstractPointcut based on the given expression
     *
     * @param string $expression Expression specifying a certain pointcut
     *
     * @return \AppserverIo\Doppelgaenger\Entities\Pointcut\AbstractPointcut
     *
     * @throws \InvalidArgumentException
     */
    public function getInstance($expression)
    {
        // first of all we have to get the type of the pointcut
        $isNegated = false;
        if (strpos($expression, '!') !== false) {

            $isNegated = true;
            $expression = str_replace('!', '', $expression);
        }
        $type = trim(strstr($expression, '(', true));

        // build up the class name and check if we know a class like that
        $class = '\AppserverIo\Doppelgaenger\Entities\Pointcuts\\' . ucfirst($type) . 'Pointcut';
        if (!class_exists($class)) {

            throw new \InvalidArgumentException('Unknown pointcut type ' . $type);
        }

        $pointcut = new $class(trim(str_replace($type, '', $expression), '() '), $isNegated);
        $pointcut->lock();

        return $pointcut;
    }
}
