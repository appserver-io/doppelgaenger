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
     * @Before("weave(Logger->error(__FUNCTION__))")
     * @Before("if($param1===1) && weave(Logger->error(__METHOD__))")
     * @Before("(if($param1===1) || if($param1===2)) && weave(Logger->error(__METHOD__))")
     * @After("(if($param1===1) || (if($param1 > 2) && if($param1 < 5))) && weave(Logger->error(__METHOD__))")
     */
    public function iHaveDoctrineSeveralAnnotations($param1)
    {

    }

    /**
     * @Before("weave(Logger->log(__METHOD__))")
     */
    public function iHaveDoctrineAnnotations($param1)
    {

    }
}
