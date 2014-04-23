<?php

namespace UdbTest\Domain\User\Storage;

use Udb\Domain\User\Storage\LdapStorage;
use Zend\Stdlib\Parameters;


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
        $filterConvertor = $this->getMock('Udb\Domain\User\Storage\FilterConvertor\FilterConvertorInterface');
        $this->storage->setFilterConvertor($filterConvertor);
        
        $this->assertSame($filterConvertor, $this->storage->getFilterConvertor());
    }


    public function testGetFilterConvertorWithImplicitValue()
    {
        $filterConvertor = $this->storage->getFilterConvertor();
        
        $this->assertInstanceOf('Udb\Domain\User\Storage\FilterConvertor\FilterConvertorInterface', $filterConvertor);
    }
}