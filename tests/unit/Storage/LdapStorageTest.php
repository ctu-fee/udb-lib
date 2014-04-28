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


    public function testFetchGroupRecords()
    {
        $baseDn = 'o=base';
        $sizelimit = 42;
        $scope = \Zend\Ldap\Ldap::SEARCH_SCOPE_SUB;
        $filter = $this->createFilterMock();
        $ldapFilter = '(foo=bar)';
        $records = $this->createLdapCollection();
        
        $this->storage->setParam(LdapStorage::PARAM_GROUP_BASE_DN, $baseDn);
        $this->storage->setParam(LdapStorage::PARAM_GROUP_SEARCH_SIZE_LIMIT, $sizelimit);
        
        $filterConvertor = $this->createFilterConvertorMock();
        $filterConvertor->expects($this->once())
            ->method('convert')
            ->with($filter)
            ->will($this->returnValue($ldapFilter));
        
        $this->storage->setFilterConvertor($filterConvertor);
        
        $ldapClient = $this->createLdapClientMock();
        $ldapClient->expects($this->once())
            ->method('search')
            ->with(array(
            'filter' => $ldapFilter,
            'baseDn' => $baseDn,
            'scope' => $scope,
            'sizelimit' => $sizelimit
        ))
            ->will($this->returnValue($records));
        
        $this->storage->setLdapClient($ldapClient);
        
        $this->assertSame($records, $this->storage->fetchGroupRecords($filter));
    }
    
    /*
     * 
     */
    protected function createFilterMock()
    {
        $filter = $this->getMock('Udb\Domain\Repository\Filter\FilterInterface');
        
        return $filter;
    }


    protected function createFilterConvertorMock()
    {
        $filterConvertor = $this->getMock('Udb\Domain\Storage\FilterConvertor\FilterConvertorInterface');
        
        return $filterConvertor;
    }


    protected function createLdapClientMock()
    {
        $ldapClient = $this->getMock('Zend\Ldap\Ldap');
        
        return $ldapClient;
    }


    protected function createLdapCollection()
    {
        $col = $this->getMockBuilder('Zend\Ldap\Collection')
            ->disableOriginalConstructor()
            ->getMock();
        
        return $col;
    }
}