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
}