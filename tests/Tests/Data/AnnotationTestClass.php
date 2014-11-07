<?php
/**
 * AppserverIo\Doppelgaenger\Tests\Data\AnnotationTestClass
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */
namespace AppserverIo\Doppelgaenger\Tests\Data;

/**
 * @package     AppserverIo\Doppelgaenger
 * @subpackage  Tests
 * @copyright   Copyright (c) 2013 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Bernhard Wick <b.wick@techdivision.com>
 *
 */
class AnnotationTestClass
{
    /**
     * @param array<\Exception>   $value
     */
    public function typeCollection($value)
    {

    }

    /**
     * @return array<\Exception>
     */
    public function typeCollectionReturn($value)
    {
        return $value;
    }

    /**
     * @param null|\Exception|string $value
     */
    public function orCombinator($value)
    {

    }

    /**
     * @param
     *          $param1
     */
    private function iHaveBadAnnotations($param1)
    {

    }

    /**
     * @Before(execute="Logger->log(__METHOD__)")
     */
    public function iHaveDoctrineAnnotations($param1)
    {

    }

    /**
     * @Before(execute={"Logger->error(__METHOD__)","Logger->debug(__METHOD__)"})
     */
    public function iHaveDoctrineSeveralAnnotations($param1)
    {

    }

    /**
     * @Process(execute""$this->remoteCall(__FUNCTION__, func_get_args())")
     */
    public function iHaveADifferentProcessing($param1)
    {

    }
}
