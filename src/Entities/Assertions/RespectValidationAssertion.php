<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Assertions\RespectValidationAssertion
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

namespace AppserverIo\Doppelgaenger\Entities\Assertions;

use AppserverIo\Doppelgaenger\Dictionaries\ReservedKeywords;

/**
 * This class will enable us to check assertions based on the respect/validation library
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class RespectValidationAssertion extends RawAssertion
{

    /**
     * Return a string representation of the classes logic as a piece of PHP code.
     * Used to transfer important logic into generated code
     *
     * @return string
     */
    public function toCode()
    {
        $code = 'try {
            if ('. $this->getInvertString() .') {
                ' . ReservedKeywords::FAILURE_VARIABLE . '[] = \'(' . str_replace('\'', '"', $this->getString()) . ')\';
            }
        } catch (\Respect\Validation\Exceptions\ExceptionInterface $e) {
            if ($e instanceof \Respect\Validation\Exceptions\NestedValidationExceptionInterface) {
                ' . ReservedKeywords::FAILURE_VARIABLE . '[] = $e->getFullMessage();
            } else {
                ' . ReservedKeywords::FAILURE_VARIABLE . '[] = $e->getMainMessage();
            }
        }';

        return $code;
    }
}
