<?php

namespace UdbTest\Domain\Util;

use Zend\Stdlib\Parameters;


class ObjectParamsTraitTest extends \PHPUnit_Framework_TestCase
{


    public function testSetGetParams()
    {
        $params = new Parameters();
        
        $trait = $this->getObjectForTrait('Udb\Domain\Util\ObjectParamsTrait');
        $trait->setParams($params);
        
        $this->assertSame($params, $trait->getParams());
    }


    public function testGetParam()
    {
        $params = new Parameters(array(
            'foo' => 'bar'
        ));
        
        $trait = $this->getObjectForTrait('Udb\Domain\Util\ObjectParamsTrait');
        $trait->setParams($params);
        
        $this->assertSame('bar', $trait->getParam('foo'));
    }


    public function testGetParamWithDefaultValue()
    {
        $params = new Parameters(array(
            'foo' => 'bar'
        ));
        
        $trait = $this->getObjectForTrait('Udb\Domain\Util\ObjectParamsTrait');
        $trait->setParams($params);
        
        $this->assertSame('bar1', $trait->getParam('foo1', 'bar1'));
    }
}