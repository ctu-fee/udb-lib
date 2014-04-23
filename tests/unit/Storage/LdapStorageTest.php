<?php

namespace UdbTest\Domain\Storage;

use Zend\Stdlib\Parameters;
use Udb\Domain\Storage\LdapStorage;


class LdapStorageTest extends \PHPUnit_Framework_TestCase
{

    protected $storage;


    public function setUp()
    {
        $params = new Parameters();
        $ldapClient = $this->getMock('Zend\Ldap\Ldap');
        
        $this->storage = new LdapStorage($ldapClient, $params);
    }


    public function testConstructor()
    {
        $params = new Parameters();
        $ldapClient = $this->getMock('Zend\Ldap\Ldap');
        
        $storage = new LdapStorage($ldapClient, $params);
        
        $this->assertSame($ldapClient, $storage->getLdapClient());
        $this->assertSame($params, $storage->getParams());
    }


    public function testSetFilterConvertor()
    {
        $filterConvertor = $this->getMock('Udb\Domain\Storage\FilterConvertor\FilterConvertorInterface');
        $this->storage->setFilterConvertor($filterConvertor);
        
        $this->assertSame($filterConvertor, $this->storage->getFilterConvertor());
    }


    public function testGetFilterConvertorWithImplicitValue()
    {
        $filterConvertor = $this->storage->getFilterConvertor();
        
        $this->assertInstanceOf('Udb\Domain\Storage\FilterConvertor\FilterConvertorInterface', $filterConvertor);
    }


    public function testGetGroupDnByName()
    {
        $this->storage->setParam('group_base_dn', 'ou=groups,o=example.org');
        $this->assertSame('foo=Test Group,ou=groups,o=example.org', $this->storage->getGroupDnByName('Test Group', 'foo'));
    }


    public function testGetGroupDnByNameWithImplicitAttrName()
    {
        $this->storage->setParam('group_base_dn', 'ou=groups,o=example.org');
        $this->assertSame('cn=Test Group,ou=groups,o=example.org', $this->storage->getGroupDnByName('Test Group'));
    }
}