<?php

namespace UdbTest\Domain\Storage;

use Zend\Stdlib\Parameters;
use Zend\Config\Config;
use Zend\Ldap\Ldap;
use Udb\Domain\Storage\LdapStorage;
use Zend\Ldap\Attribute;


class LdapGroupStorageTest extends \PHPUnit_Framework_TestCase
{

    protected $config;

    protected $ldapClient;

    protected $storage;


    public function setUp()
    {
        $this->config = new Config(require CONFIG_FILE);
        $ldapClient = new Ldap($this->config->get('ldap_client'));
        $params = new Parameters($this->config->get('ldap_storage')->toArray());
        
        $this->storage = new LdapStorage($ldapClient, $params);
    }


    public function tearDown()
    {
        $this->storage->getLdapClient()->disconnect();
    }


    public function testFetchGroupRecord()
    {
        $groupName = $this->config->tests->get('test_group_name');
        
        $record = $this->storage->fetchGroupRecord($groupName);
        
        $this->assertInternalType('array', $record);
        $this->assertSame($groupName, $record['cn'][0]);
    }


    public function testFetchGroupRecordWithObjectNotFound()
    {
        $this->setExpectedException('Udb\Domain\Storage\Exception\ObjectNotFoundException');
        
        $record = $this->storage->fetchGroupRecord('some nonexisting group');
    }


    public function testFetchGroupRecords()
    {
        $records = $this->storage->fetchGroupRecords();
        $this->assertInstanceOf('Zend\Ldap\Collection', $records);
    }


    public function testAddAndRemoveGroup()
    {
        $uid = $this->config->tests->get('test_admin_uid');
        $this->storage->setProxyUserByUid($uid);
        
        $groupName = 'Test Group #42';
        $data = array();
        
        $this->storage->addGroup($groupName, $data);
        $groupRecord = $this->storage->fetchGroupRecord($groupName);
        
        $this->assertSame($groupName, $groupRecord['cn'][0]);
        
        $this->storage->removeGroup($groupName);
        
        try {
            $groupRecord = $this->storage->fetchGroupRecord($groupName);
        } catch (\Exception $e) {
            $this->assertInstanceOf('Udb\Domain\Storage\Exception\ObjectNotFoundException', $e);
            return;
        }
        
        $this->fail('ObjectNotFoundException has not been thrown');
    }


    public function testAddAndRemoveGroupMember()
    {
        $uid = $this->config->tests->get('test_admin_uid');
        $this->storage->setProxyUserByUid($uid);
        
        $groupName = $this->config->tests->get('test_group_name');
        $testUserUid = $this->config->tests->get('test_user_uid');
        
        $this->storage->addGroupMember($groupName, $testUserUid);
        $memberRecords = $this->storage->fetchGroupMemberRecords($groupName);
        
        $this->assertSame($testUserUid, $memberRecords[0]['uid'][0]);
        
        $this->storage->removeGroupMember($groupName, $testUserUid);
        
        $this->assertEmpty($this->storage->fetchGroupMemberRecords($groupName));
    }


    public function testAddAndRemoveGroupOwner()
    {
        $uid = $this->config->tests->get('test_admin_uid');
        $this->storage->setProxyUserByUid($uid);
        
        $groupName = 'Test Group #42';
        $testUserUid = $this->config->tests->get('test_user_uid');
        
        // create the tes group
        $this->storage->addGroup($groupName);
        // add the test user as an owner
        $this->storage->addGroupOwner($groupName, $testUserUid);
        
        // check owners
        $owners = $this->storage->fetchGroupOwnerRecords($groupName);
        $this->assertSame($testUserUid, $owners[0]['uid'][0]);
        
        /* WAITING FOR IMPLEMENTATION AT THE LDAP SIDE
        // set the test user as a proxy user and check if he is really an owner by adding a member
        $this->storage->setProxyUserByUid($testUserUid);
        $this->storage->addGroupMember($groupName, $testUserUid);
        */
        
        // remove the test user as an owner
        $this->storage->removeGroupOwner($groupName, $testUserUid);
        
        // check owners
        $owners = $this->storage->fetchGroupOwnerRecords($groupName);
        $this->assertEmpty($owners);
        
        $this->storage->removeGroup($groupName);
    }


    public function testFetchUserGroupRecords()
    {
        $testUserUid = $this->config->tests->get('test_user_uid');
        $uid = $this->config->tests->get('test_admin_uid');
        $this->storage->setProxyUserByUid($uid);
        
        $group1 = 'Test Group #1';
        $group2 = 'Test Group #2';
        
        $this->storage->addGroup($group1);
        $this->storage->addGroup($group2);
        
        $this->storage->addGroupMember($group1, $testUserUid);
        $this->storage->addGroupMember($group2, $testUserUid);
        
        $userGroups = $this->storage->fetchUserGroupRecords($testUserUid);
        
        $this->storage->removeGroup($group1);
        $this->storage->removeGroup($group2);
        
        $this->assertSame($group1, $userGroups[0]['cn'][0]);
        $this->assertSame($group2, $userGroups[1]['cn'][0]);
    }
}