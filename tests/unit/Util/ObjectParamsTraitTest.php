<?php

namespace UdbTest\Domain\Util;

use Zend\Stdlib\Parameters;


class ObjectParamsTraitTest extends \PHPUnit_Framework_TestCase
{


    public function testSetGetParams()
    {
        $params = new Parameters();
        
        $trait = $this->createTraitMock();
        $trait->setParams($params);
        
        $this->assertSame($params, $trait->getParams());
    }


    public function testGetParamsWithImplicitValue()
    {
        $trait = $this->createTraitMock();
        
        $this->assertInstanceOf('Zend\Stdlib\Parameters', $trait->getParams());
    }


    public function testGetParam()
    {
        $params = new Parameters(array(
            'foo' => 'bar'
        ));
        
        $trait = $this->createTraitMock();
        $trait->setParams($params);
        
        $this->assertSame('bar', $trait->getParam('foo'));
    }


    public function testGetParamWithDefaultValue()
    {
        $params = new Parameters(array(
            'foo' => 'bar'
        ));
        
        $trait = $this->createTraitMock();
        $trait->setParams($params);
        
        $this->assertSame('bar1', $trait->getParam('foo1', 'bar1'));
    }


    public function testGetRequiredParam()
    {
        $trait = $this->createTraitMock();
        $trait->setParam('foo', 'bar');
        
        $this->assertSame('bar', $trait->getParam('foo'));
    }


    public function testGetRequiredParamWithMissingParam()
    {
        $this->setExpectedException('Udb\Domain\Util\Exception\MissingParamException', 'Missing required parameter');
        
        $trait = $this->createTraitMock();
        $trait->getRequiredParam('missing_param');
    }


    public function testSetParam()
    {
        $trait = $this->createTraitMock();
        $this->assertNull($trait->getParam('foo'));
        
        $trait->setParam('foo', 'bar');
        $this->assertSame('bar', $trait->getParam('foo'));
    }


    protected function createTraitMock()
    {
        $trait = $this->getObjectForTrait('Udb\Domain\Util\ObjectParamsTrait');
        
        return $trait;
    }
}