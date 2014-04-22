<?php

namespace UdbTest\Domain\User\Storage\FieldMap;

use Udb\Domain\User\Storage\FieldMap\LdapFieldMap;


class LdapFieldMapTest extends \PHPUnit_Framework_TestCase
{

    protected $map;


    public function setUp()
    {
        $this->map = new LdapFieldMap();
    }


    public function testSetGet()
    {
        $fieldMap = array(
            'foo' => 'bar'
        );
        
        $this->map->setFieldMap($fieldMap);
        
        $this->assertSame($fieldMap, $this->map->getFieldMap());
    }


    public function testFieldToStorageField()
    {
        $fieldMap = array(
            'foo1' => 'bar1',
            'foo2' => 'bar2'
        );
        
        $this->map->setFieldMap($fieldMap);
        
        $this->assertSame('bar1', $this->map->fieldToStorageField('foo1'));
        $this->assertSame('bar2', $this->map->fieldToStorageField('foo2'));
    }


    public function testFieldToStorageFieldWithUnknownField()
    {
        $this->assertNull($this->map->fieldToStorageField('unknown-field'));
    }


    public function testStorageFieldToField()
    {
        $fieldMap = array(
            'foo1' => 'bar1',
            'foo2' => 'bar2'
        );
        
        $this->map->setFieldMap($fieldMap);
        
        $this->assertSame('foo1', $this->map->storageFieldToField('bar1'));
        $this->assertSame('foo2', $this->map->storageFieldToField('bar2'));
    }


    public function testStorageFieldToFieldWithUnknownField()
    {
        $this->assertNull($this->map->storageFieldToField('unknown-storage-field'));
    }
}