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


    public function testAddGroup()
    {
        $groupName = 'Foo Group';
        $data = array(
            'foo' => 'bar'
        );
        $groupEntry = array(
            'cn' => 'Foo Group'
        );
        $groupDn = 'cn=Foo Group,o=example.org';
        
        $storage = $this->getMockBuilder('Udb\Domain\Storage\LdapStorage')
            ->disableOriginalConstructor()
            ->setMethods(array(
            'createGroupEntry',
            'getGroupDnByName'
        ))
            ->getMock();
        $storage->expects($this->once())
            ->method('createGroupEntry')
            ->with($groupName, $data)
            ->will($this->returnValue($groupEntry));
        $storage->expects($this->once())
            ->method('getGroupDnByName')
            ->with($groupName)
            ->will($this->returnValue($groupDn));
        
        $ldapClient = $this->createLdapClientMock();
        $ldapClient->expects($this->once())
            ->method('add')
            ->with($groupDn, $groupEntry);
        $storage->setLdapClient($ldapClient);
        
        $storage->addGroup($groupName, $data);
    }


    public function testRemoveGroup()
    {
        $groupName = 'Foo Group';
        $groupDn = 'cn=Foo Group,o=example.org';
        
        $storage = $this->getMockBuilder('Udb\Domain\Storage\LdapStorage')
            ->disableOriginalConstructor()
            ->setMethods(array(
            'getGroupDnByName'
        ))
            ->getMock();
        $storage->expects($this->once())
            ->method('getGroupDnByName')
            ->with($groupName)
            ->will($this->returnValue($groupDn));
        
        $ldapClient = $this->createLdapClientMock();
        $ldapClient->expects($this->once())
            ->method('delete')
            ->with($groupDn);
        $storage->setLdapClient($ldapClient);
        
        $storage->removeGroup($groupName);
    }


    public function testFetchGroupMemberRecords()
    {
        $groupName = 'Foo Group';
        $groupDn = 'cn=Foo Group,o=example.org';
        $attrName = 'member';
        $memberDns = array(
            'uid=foo',
            'uid=bar'
        );
        $memberRecords = array(
            array(
                'dn' => 'uid=foo'
            ),
            array(
                'dn' => 'uid=bar'
            )
        );
        
        $groupNode = $this->createLdapNodeMock();
        $groupNode->expects($this->once())
            ->method('getAttribute')
            ->with($attrName)
            ->will($this->returnValue($memberDns));
        
        $storage = $this->getMockBuilder('Udb\Domain\Storage\LdapStorage')
            ->disableOriginalConstructor()
            ->setMethods(array(
            'getGroupDnByName',
            'getNode'
        ))
            ->getMock();
        
        $storage->setParam(LdapStorage::PARAM_GROUP_MEMBER_ATTRIBUTE_NAME, $attrName);
        
        $storage->expects($this->once())
            ->method('getGroupDnByName')
            ->with($groupName)
            ->will($this->returnValue($groupDn));
        $storage->expects($this->at(1))
            ->method('getNode')
            ->with($groupDn)
            ->will($this->returnValue($groupNode));
        
        for ($i = 0; $i < 2; $i ++) {
            $storage->expects($this->at($i + 2))
                ->method('getNode')
                ->with($memberDns[$i], true)
                ->will($this->returnValue($memberRecords[$i]));
        }
        
        $this->assertSame($memberRecords, $storage->fetchGroupMemberRecords($groupName));
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


    protected function createLdapNodeMock()
    {
        $node = $this->getMockBuilder('Zend\Ldap\Node')
            ->disableOriginalConstructor()
            ->getMock();
        
        return $node;
    }
}