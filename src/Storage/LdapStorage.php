<?php

namespace Udb\Domain\Storage;

use Zend\Stdlib\Parameters;
use Zend\Ldap\Ldap;
use Udb\Domain\Storage\FilterConvertor\SimpleFilterAndToLdapFilterConvertor;
use Udb\Domain\Storage\FilterConvertor\FilterConvertorInterface;
use Udb\Domain\Repository\Filter\FilterInterface;
use Udb\Domain\Util\ObjectParamsTrait;


class LdapStorage implements StorageInterface
{
    
    use ObjectParamsTrait;

    const CONTROL_PROXY_AUTH = '2.16.840.1.113730.3.4.18';

    const PARAM_USER_SEARCH_BASE_DN = 'user_search_base_dn';

    const PARAM_USER_SEARCH_SIZE_LIMIT = 'user_search_size_limit';

    /**
     * @var Ldap
     */
    protected $ldapClient;

    /**
     * @var FilterConvertorInterface
     */
    protected $filterConvertor;


    /**
     * Constructor.
     * 
     * @param Ldap $ldapClient
     * @param Parameters $params
     */
    public function __construct(Ldap $ldapClient, Parameters $params)
    {
        $this->setParams($params);
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
     * @return FilterConvertorInterface
     */
    public function getFilterConvertor()
    {
        if (! $this->filterConvertor instanceof FilterConvertorInterface) {
            $this->filterConvertor = new SimpleFilterAndToLdapFilterConvertor();
        }
        
        return $this->filterConvertor;
    }


    /**
     * @param FilterConvertorInterface $filterConvertor
     */
    public function setFilterConvertor(FilterConvertorInterface $filterConvertor)
    {
        $this->filterConvertor = $filterConvertor;
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


    /**
     * {@inhertidoc}
     * @see \Udb\Domain\User\Storage\StorageInterface::fetchUserRecords()
     */
    public function fetchUserRecords(FilterInterface $filter = null)
    {
        $ldapFilter = $this->getFilterConvertor()->convert($filter);
        
        $records = $this->getLdapClient()->search(array(
            'filter' => $ldapFilter,
            'baseDn' => $this->getParam(self::PARAM_USER_SEARCH_BASE_DN),
            'scope' => Ldap::SEARCH_SCOPE_SUB,
            'sizelimit' => $this->getParam(self::PARAM_USER_SEARCH_SIZE_LIMIT, 100)
        ));
        
        return $records;
    }


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