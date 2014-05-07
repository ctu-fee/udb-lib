<?php

namespace UdbTest\Domain\Util;

use Udb\Domain\Util\InitCollectionTrait;


class DummyClass
{
    use InitCollectionTrait;


    public function init($collection, $requiredCollectionClass)
    {
        return $this->initCollection($collection, $requiredCollectionClass);
    }
}


class InitCollectionTraitTest extends \PHPUnit_Framework_TestCase
{

    protected $dummy;


    public function setUp()
    {
        $this->dummy = new DummyClass();
    }


    public function testInitCollectionWithUndefinedClass()
    {
        $this->setExpectedException('InvalidArgumentException', 'Unknown collection class');
        
        $collectionClass = 'Some\Undefined\Class';
        $this->dummy->init(array(), $collectionClass);
    }


    public function testInitCollectionWithInvalidClass()
    {
        $this->setExpectedException('InvalidArgumentException', 'Provided collection class');
        
        $collectionClass = 'stdClass';
        $this->dummy->init(array(), $collectionClass);
    }


    public function testInitCollectionWithValidCollection()
    {
        $collectionClass = 'Udb\Domain\Entity\Collection\EmailAddressCollection';
        $collection = $this->getMock($collectionClass);
        
        $this->assertSame($collection, $this->dummy->init($collection, $collectionClass));
    }


    public function testInitCollectionWithArray()
    {
        $collectionClass = 'Udb\Domain\Entity\Collection\EmailAddressCollection';
        $collectionData = array(
            'foo@bar',
            'test@bar'
        );
        
        $collection = $this->dummy->init($collectionData, $collectionClass);
        
        $this->assertSame($collectionData[0], $collection->get(0)
            ->getValue());
        $this->assertSame($collectionData[1], $collection->get(1)
            ->getValue());
    }


    public function testInitCollectionWithInvalidCollectionInstance()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid collection value');
        
        $collectionClass = 'Udb\Domain\Entity\Collection\EmailAddressCollection';
        $collection = $this->getMock('Udb\Domain\Entity\Collection\CollectionInterface');
        
        $this->dummy->init($collection, $collectionClass);
    }


    public function testInitCollectionWithInvalidType()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid collection value');
        
        $collectionClass = '\Udb\Domain\Entity\Collection\EmailAddressCollection';
        $this->dummy->init(123, $collectionClass);
    }
}
