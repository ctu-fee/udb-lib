<?php

namespace UdbTest\Domain\Entity\Collection;


class AbstractStringValueObjectCollectionTest extends \PHPUnit_Framework_TestCase
{


    public function testToPlainArray()
    {
        $obj1 = $this->createValueObject('foo');
        $obj2 = $this->createValueObject('bar');
        
        $col = $this->getMockBuilder('Udb\Domain\Entity\Collection\AbstractStringValueObjectCollection')->getMockForAbstractClass();
        $col->append($obj1);
        $col->append($obj2);
        
        $this->assertSame(array(
            'foo',
            'bar'
        ), $col->toPlainArray());
    }


    protected function createValueObject($value)
    {
        $object = $this->getMockBuilder('Udb\Domain\Entity\AbstractStringValueObject')
            ->setConstructorArgs(array(
            $value
        ))
            ->getMockForAbstractClass();
        
        return $object;
    }
}