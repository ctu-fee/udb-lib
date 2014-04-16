<?php

namespace UdbTest\Domain\Entity\Collection;


class AbstractCollectionTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructorWithImplicitValue()
    {
        $collection = $this->createAbstractCollection();
        $this->assertCount(0, $collection);
    }


    public function testConstructorWithExplicitValue()
    {
        $data = array(
            'value1',
            'value2'
        );
        
        $collection = $this->createAbstractCollection($data);
        $this->assertCount(2, $collection);
        $this->assertSame('value1', $collection->get(0));
        $this->assertSame('value2', $collection->get(1));
    }


    public function testSetItemsWithInvalidData()
    {
        $this->setExpectedException('Zend\Stdlib\Exception\InvalidArgumentException', 'Argument must be an array or Traversable');
        
        $invalidData = 'some string';
        $collection = $this->createAbstractCollection();
        
        $collection->setItems($invalidData);
    }


    public function testSetItemsWithValidData()
    {
        $data = array(
            'value1',
            'value2'
        );
        
        $collection = $this->createAbstractCollection();
        $collection->setItems($data);
        
        $this->assertCount(2, $collection);
        $this->assertSame('value1', $collection->get(0));
        $this->assertSame('value2', $collection->get(1));
    }


    public function testAppend()
    {
        $collection = $this->createAbstractCollection();
        $collection->append('value1');
        $this->assertSame('value1', $collection->get(0));
        $collection->append('value2');
        $this->assertSame('value2', $collection->get(1));
    }


    public function testAppendWithInvalidItem()
    {
        $this->setExpectedException('Udb\Domain\Entity\Collection\Exception\InvalidItemException', 'Invalid item');
        
        $item = 'value1';
        
        $collection = $this->getMockBuilder('Udb\Domain\Entity\Collection\AbstractCollection')
            ->setMethods(array(
            'isValid'
        ))
            ->getMockForAbstractClass();
        $collection->expects($this->once())
            ->method('isValid')
            ->with($item)
            ->will($this->returnValue(false));
        
        $collection->append($item);
    }


    public function testCount()
    {
        $collection = $this->createAbstractCollection();
        
        $this->assertCount(0, $collection);
        
        $collection->append('value1');
        $this->assertCount(1, $collection);
        
        $collection->append('value2');
        $this->assertCount(2, $collection);
        
        $collection->append('value3');
        $this->assertCount(3, $collection);
    }


    public function testGetWithNonexistentValue()
    {
        $collection = $this->createAbstractCollection();
        $this->assertNull($collection->get(100));
    }


    public function testGetWithDefaultValue()
    {
        $collection = $this->createAbstractCollection();
        $this->assertSame('default', $collection->get(100, 'default'));
    }


    public function testSetGet()
    {
        $collection = $this->createAbstractCollection();
        $this->assertNull($collection->get('foo'));
        
        $collection->set('foo', 'bar');
        $this->assertSame('bar', $collection->get('foo'));
    }


    public function testSetWithInvalidItem()
    {
        $this->setExpectedException('Udb\Domain\Entity\Collection\Exception\InvalidItemException', 'Invalid item');
        
        $item = 'value1';
        
        $collection = $this->getMockBuilder('Udb\Domain\Entity\Collection\AbstractCollection')
            ->setMethods(array(
            'isValid'
        ))
            ->getMockForAbstractClass();
        $collection->expects($this->once())
            ->method('isValid')
            ->with($item)
            ->will($this->returnValue(false));
        
        $collection->set('foo', $item);
    }


    public function testToArray()
    {
        $collection = $this->createAbstractCollection();
        $collection->append('foo');
        $collection->append('bar');
        
        $this->assertSame(array(
            'foo',
            'bar'
        ), $collection->toArray());
    }


    public function testGetIterator()
    {
        $collection = $this->createAbstractCollection();
        $this->assertInstanceOf('ArrayIterator', $collection->getIterator());
    }


    public function testOffsetExists()
    {
        $collection = $this->createAbstractCollection();
        
        $this->assertFalse($collection->offsetExists('foo'));
        
        $collection->set('foo', 'bar');
        
        $this->assertTrue($collection->offsetExists('foo'));
    }


    public function testOffsetGetSet()
    {
        $collection = $this->createAbstractCollection();
        $collection->offsetSet('foo', 'bar');
        
        $this->assertSame('bar', $collection->offsetGet('foo'));
    }


    public function testOffsetUnset()
    {
        $collection = $this->createAbstractCollection();
        $collection->offsetSet('foo', 'bar');
        
        $this->assertSame('bar', $collection->get('foo'));
        
        $collection->offsetUnset('foo');
        $this->assertNull($collection->get('foo'));
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createAbstractCollection($data = array())
    {
        $collection = $this->getMockBuilder('Udb\Domain\Entity\Collection\AbstractCollection')
            ->setConstructorArgs(array(
            $data
        ))
            ->getMockForAbstractClass();
        
        return $collection;
    }
}