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
 * @subpackage Entities
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\Dictionaries;

/**
 * AppserverIo\Doppelgaenger\Dictionaries\PointcutPatterns
 *
 * Dictionary class for the possible match patterns of pointcuts
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Dictionaries
 * @author     Bernhard Wick <bw@appserver.io>
 * @copyright  2014 TechDivision GmbH - <info@appserver.io>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.appserver.io/
 */
class PointcutPatterns
{
    /**
     * Expression patterns
     *
     * @var string EXPRESSION
     */
    const EXPRESSION = 'Expression';

    /**
     * Pointcut patterns
     *
     * @var string POINTCUT
     */
    const POINTCUT = 'Pointcut';

    /**
     * Signature patterns
     *
     * @var string SIGNATURE
     */
    const SIGNATURE = 'Signature';

    /**
     * Type patterns
     *
     * @var string TYPE
     */
    const TYPE = 'Type';

    /**
     * TypePattern patterns
     *
     * @var string TYPEPATTERN
     */
    const TYPEPATTERN = 'TypePattern';
}
