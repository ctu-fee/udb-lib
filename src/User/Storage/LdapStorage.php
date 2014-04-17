<?php

namespace Udb\Domain\User\Storage;

use Zend\Ldap\Ldap;


class LdapStorage implements StorageInterface
{

    const CONTROL_PROXY_AUTH = '2.16.840.1.113730.3.4.18';

    /**
     * @var Ldap
     */
    protected $ldapClient;


    /**
     * Constructor.
     * 
     * @param Ldap $ldapClient
     */
    public function __construct(Ldap $ldapClient)
    {
        $this->setLdapClient($ldapClient);
    }


    /**
     * @return Ldap
     */
    public function getLdapClient()
    {
        return $this->ldapClient;
    }


    /**
     * @param Ldap $ldapClient
     */
    public function setLdapClient(Ldap $ldapClient)
    {
        $this->ldapClient = $ldapClient;
    }


    /**
     * {@inhertidoc}
     * @see \Udb\Domain\User\Storage\StorageInterface::fetchUserRecord()
     */
    public function fetchUserRecord($uid)
    {
        return $this->getUserNodeByUid($uid)->toArray();
    }


    /**
     * {@inhertidoc}
     * @see \Udb\Domain\User\Storage\StorageInterface::updateUserRecord()
     */
    public function updateUserRecord($uid, array $data)
    {
        $node = $this->getUserNodeByUid($uid);
        foreach ($data as $name => $value) {
            $node->setAttribute($name, $value);
        }
        
        $node->update();
    }


    public function fetchUserRecords(array $filters = array())
    {}


    /**
     * Returns true, if the storage supports proxy authentication.
     * 
     * @return boolean
     */
    public function supportsProxyAuthentication()
    {
        return (in_array(self::CONTROL_PROXY_AUTH, $this->getSupportedControls()));
    }


    /**
     * Returns of OID values indicating the supported controls of the storage.
     * 
     * @return array
     */
    public function getSupportedControls()
    {
        $supportedControl = $this->getLdapClient()
            ->getRootDse()
            ->getAttribute('supportedcontrol');
        
        if (! is_array($supportedControl)) {
            $supportedControl = array();
        }
        
        return $supportedControl;
    }


    /**
     * Returns the corresponding user DN.
     *
     * @param string $uid
     * @throws LdapException\GeneralException
     * @return string
     */
    public function getUserDnByUid($uid)
    {
        try {
            $userDn = $this->getLdapClient()->getCanonicalAccountName($uid);
        } catch (\Exception $e) {
            throw new Exception\GeneralException(sprintf("Error getting user DN for uid=%s: [%s] %s", $uid, get_class($e), $e->getMessage()), null, $e);
        }
        
        return $userDn;
    }


    /**
     * Sets a proxy user authorization by UID.
     * 
     * @param string $uid
     * @return boolean
     */
    public function setProxyUserByUid($uid)
    {
        return $this->setProxyUserByDn($this->getUserDnByUid($uid));
    }


    /**
     * Sets a proxy user authorization by user DN.
     * 
     * @param string $userDn
     * @throws Exception\SetOptionException
     * @return boolean
     */
    public function setProxyUserByDn($userDn)
    {
        $serverControls = array(
            array(
                'oid' => self::CONTROL_PROXY_AUTH,
                'value' => 'dn:' . $userDn,
                'iscritical' => true
            )
        );
        
        $ldapResource = $this->getLdapClient()->getResource();
        if (! ldap_set_option($ldapResource, LDAP_OPT_SERVER_CONTROLS, $serverControls)) {
            throw new Exception\SetOptionException(sprintf("Error setting proxy authentication for DN '%s'", $userDn));
        }
        
        return true;
    }


    /**
     * Searches for the corresponding user node by uid and returns it.
     *
     * @param string $uid
     * @throws Exception\ObjectNotFoundException
     * @return \Zend\Ldap\Node|null
     */
    public function getUserNodeByUid($uid)
    {
        $ldap = $this->getLdapClient();
        $userDn = $this->getUserDnByUid($uid);
        
        try {
            $node = $ldap->getNode($userDn);
        } catch (\Exception $e) {
            throw new Exception\ObjectNotFoundException(sprintf("Node with uid=%s (%s) cannot be fetched: [%s] %s", $uid, $userDn, get_class($e), $e->getMessage()), null, $e);
        }
        
        return $node;
    }
}