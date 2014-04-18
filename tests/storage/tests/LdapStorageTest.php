<?php

namespace UdbTest\Domain\Storage;

use Zend\Stdlib\Parameters;
use Zend\Config\Config;
use Zend\Ldap\Ldap;
use Udb\Domain\User\Storage\LdapStorage;


class LdapStorageTest extends \PHPUnit_Framework_TestCase
{

    protected $config;

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


    public function testConstructor()
    {
        $this->assertInstanceOf('Zend\Ldap\Ldap', $this->storage->getLdapClient());
    }


    public function testGetSupportedControls()
    {
        $supportedControls = $this->storage->getSupportedControls();
        $this->assertNotEmpty($supportedControls);
    }


    public function testSupportsProxyAuthentication()
    {
        $this->assertTrue($this->storage->supportsProxyAuthentication());
    }


    public function testGetUserDnByUid()
    {
        $dn = $this->storage->getUserDnByUid($this->config->tests->get('testuid'));
        $this->assertSame($this->config->tests->get('testdn'), $dn);
    }


    public function testGetUserDnByUidWithNonexistentUid()
    {
        $this->setExpectedException('Udb\Domain\User\Storage\Exception\GeneralException', 'Error getting user DN');
        
        $this->storage->getUserDnByUid('somenonexistentuid');
    }


    public function testSetProxyUserByUid()
    {
        $this->assertTrue($this->storage->setProxyUserByUid($this->config->tests->get('testuid')));
    }


    public function testFetchUserRecord()
    {
        $uid = $this->config->tests->get('testuid');
        $userData = $this->storage->fetchUserRecord($uid);
        
        $this->assertSame($uid, $userData['uid'][0]);
    }


    public function testNoWritePriviledgeForTheCoreUser()
    {
        $this->setExpectedException('Zend\Ldap\Exception\LdapException', "0x32 (Insufficient access; Insufficient 'write' privilege");
        
        $uid = $this->config->tests->get('testuid');
        $this->storage->updateUserRecord($uid, array(
            'telephonenumber' => array(
                '123'
            )
        ));
    }


    public function testUpdateUserRecord()
    {
        $attr = 'telephonenumber';
        $uid = $this->config->tests->get('testuid');
        $this->storage->setProxyUserByUid($uid);
        $userData = $this->storage->fetchUserRecord($uid);
        
        /*
         * Backup current value of the attribute
         */
        $backupValue = isset($userData[$attr]) ? $userData[$attr] : array();
        
        /*
         * Set new values
         */
        $testValue = array(
            '111 222',
            '444 555'
        );
        $this->storage->updateUserRecord($uid, array(
            $attr => $testValue
        ));
        
        /*
         * Check if the new values has been set
         */
        $updatedUserData = $this->storage->fetchUserRecord($uid);
        $this->assertSame($testValue, $updatedUserData[$attr]);
        
        /*
         * Set the original values
         */
        $this->storage->updateUserRecord($uid, array(
            $attr => $backupValue
        ));
        
        /*
         * Check if the original values has been set
         */
        $originalUserData = $this->storage->fetchUserRecord($uid);
        $this->assertSame($backupValue, $originalUserData[$attr]);
    }
}