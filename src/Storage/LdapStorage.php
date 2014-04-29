<?php

namespace Udb\Domain\Storage;

use Zend\Stdlib\Parameters;
use Zend\Ldap\Ldap;
use Zend\Ldap\Attribute;
use Udb\Domain\Storage\FilterConvertor\SimpleFilterAndToLdapFilterConvertor;
use Udb\Domain\Storage\FilterConvertor\FilterConvertorInterface;
use Udb\Domain\Repository\Filter\FilterInterface;
use Udb\Domain\Util\ObjectParamsTrait;


class LdapStorage implements ProxyUserEnabledStorageInterface, UserStorageInterface, GroupStorageInterface
{
    
    use ObjectParamsTrait;

    const CONTROL_PROXY_AUTH = '2.16.840.1.113730.3.4.18';

    const PARAM_GROUP_MEMBER_ATTRIBUTE_NAME = 'group_member_attribute_name';

    const PARAM_GROUP_OWNER_ATTRIBUTE_NAME = 'group_owner_attribute_name';

    const PARAM_USER_BASE_DN = 'user_base_dn';

    const PARAM_USER_SEARCH_SIZE_LIMIT = 'user_search_size_limit';

    const PARAM_GROUP_BASE_DN = 'group_base_dn';

    const PARAM_GROUP_SEARCH_SIZE_LIMIT = 'group_search_size_limit';

    const PARAM_GROUP_OBJECT_CLASSES = 'group_object_classes';

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
     * @see \Udb\Domain\Storage\StorageInterface::supportsProxyAuthentication()
     */
    public function supportsProxyAuthentication()
    {
        return (in_array(self::CONTROL_PROXY_AUTH, $this->getSupportedControls()));
    }


    /**
     * {@inhertidoc}
     * @see \Udb\Domain\Storage\StorageInterface::setProxyUserByUid()
     */
    public function setProxyUserByUid($uid)
    {
        return $this->setProxyUserByDn($this->getUserDnByUid($uid));
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
        
        $records = $this->search(array(
            'filter' => $ldapFilter,
            'baseDn' => $this->getRequiredParam(self::PARAM_USER_BASE_DN),
            'scope' => Ldap::SEARCH_SCOPE_SUB,
            'sizelimit' => $this->getParam(self::PARAM_USER_SEARCH_SIZE_LIMIT, 100)
        ));
        
        return $records->toArray();
    }


    /**
     * {@inheritdoc}
     * @see \Udb\Domain\Storage\UserStorageInterface::fetchUserGroupRecords()
     */
    public function fetchUserGroupRecords($uid)
    {
        $userDn = $this->getUserDnByUid($uid);
        $memberFilter = sprintf("(%s=%s)", $this->getRequiredParam(self::PARAM_GROUP_MEMBER_ATTRIBUTE_NAME), $userDn);
        
        $records = $this->search(array(
            'filter' => $memberFilter,
            'baseDn' => $this->getRequiredParam(self::PARAM_GROUP_BASE_DN),
            'scope' => Ldap::SEARCH_SCOPE_SUB,
            'sizelimit' => $this->getParam(self::PARAM_GROUP_SEARCH_SIZE_LIMIT, 100)
        ));
        
        return $records->toArray();
    }


    /**
     * {@inheritdoc}
     * @see \Udb\Domain\Storage\StorageInterface::fetchGroupRecord()
     */
    public function fetchGroupRecord($groupName)
    {
        $groupDn = $this->getGroupDnByName($groupName);
        
        return $this->getNode($groupDn, true);
    }


    /**
     * {@inheritdoc}
     * @see \Udb\Domain\Storage\StorageInterface::fetchGroupRecords()
     */
    public function fetchGroupRecords(FilterInterface $filter = null)
    {
        $ldapFilter = '(cn=*)';
        if (null !== $filter) {
            $ldapFilter = $this->getFilterConvertor()->convert($filter);
        }
        
        $records = $this->search(array(
            'filter' => $ldapFilter,
            'baseDn' => $this->getRequiredParam(self::PARAM_GROUP_BASE_DN),
            'scope' => Ldap::SEARCH_SCOPE_SUB,
            'sizelimit' => $this->getParam(self::PARAM_GROUP_SEARCH_SIZE_LIMIT, 100)
        ));
        
        return $records;
    }


    /**
     * {@inheritdoc}
     * @see \Udb\Domain\Storage\StorageInterface::fetchGroupMemberRecords()
     */
    public function fetchGroupMemberRecords($groupName)
    {
        return $this->fetchUserRecordsFromGroupAttribute($groupName, $this->getRequiredParam(self::PARAM_GROUP_MEMBER_ATTRIBUTE_NAME));
    }


    /**
     * {@inheritdoc}
     * @see \Udb\Domain\Storage\StorageInterface::addGroupMember()
     */
    public function addGroupMember($groupName, $uid)
    {
        $userDn = $this->getUserDnByUid($uid);
        $groupDn = $this->getGroupDnByName($groupName);
        
        $this->appendToNodeAttribute($groupDn, $this->getRequiredParam(self::PARAM_GROUP_MEMBER_ATTRIBUTE_NAME), $userDn);
    }


    /**
     * {@inheritdoc}
     * @see \Udb\Domain\Storage\StorageInterface::removeGroupMember()
     */
    public function removeGroupMember($groupName, $uid)
    {
        $userDn = $this->getUserDnByUid($uid);
        $groupDn = $this->getGroupDnByName($groupName);
        
        $this->removeFromNodeAttribute($groupDn, $this->getRequiredParam(self::PARAM_GROUP_MEMBER_ATTRIBUTE_NAME), $userDn);
    }


    /**
     * {@inheritdoc}
     * @see \Udb\Domain\Storage\StorageInterface::fetchGroupOwnerRecords()
     */
    public function fetchGroupOwnerRecords($groupName)
    {
        return $this->fetchUserRecordsFromGroupAttribute($groupName, $this->getRequiredParam(self::PARAM_GROUP_OWNER_ATTRIBUTE_NAME));
    }


    /**
     * {@inheritdoc}
     * @see \Udb\Domain\Storage\GroupStorageInterface::addGroupOwner()
     */
    public function addGroupOwner($groupName, $uid)
    {
        $userDn = $this->getUserDnByUid($uid);
        $groupDn = $this->getGroupDnByName($groupName);
        
        $this->appendToNodeAttribute($groupDn, $this->getRequiredParam(self::PARAM_GROUP_OWNER_ATTRIBUTE_NAME), $userDn);
    }


    /**
     * {@inheritdoc}
     * @see \Udb\Domain\Storage\GroupStorageInterface::removeGroupOwner()
     */
    public function removeGroupOwner($groupName, $uid)
    {
        $userDn = $this->getUserDnByUid($uid);
        $groupDn = $this->getGroupDnByName($groupName);
        
        $this->removeFromNodeAttribute($groupDn, $this->getRequiredParam(self::PARAM_GROUP_OWNER_ATTRIBUTE_NAME), $userDn);
    }


    /**
     * {@inheritdoc}
     * @see \Udb\Domain\Storage\StorageInterface::addGroup()
     */
    public function addGroup($groupName, array $data = array())
    {
        $groupEntry = $this->createGroupEntry($groupName, $data);
        $groupDn = $this->getGroupDnByName($groupName);
        
        $this->getLdapClient()->add($groupDn, $groupEntry);
    }


    /**
     * {@inheritdoc}
     * @see \Udb\Domain\Storage\StorageInterface::removeGroup()
     */
    public function removeGroup($groupName)
    {
        $this->getLdapClient()->delete($this->getGroupDnByName($groupName));
    }


    public function setGroupAttribute($name, $value)
    {}


    /**
     * Returns of OID values indicating the supported controls of the storage.
     *
     * @throws Exception\GeneralException
     * @return array
     */
    public function getSupportedControls()
    {
        try {
            $supportedControl = $this->getLdapClient()
                ->getRootDse()
                ->getAttribute('supportedcontrol');
        } catch (\Exception $e) {
            throw new Exception\GeneralException('Cannot fetch supported controls', null, $e);
        }
        
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
     * Returns the corresponding group DN.
     * 
     * @param string $groupName
     * @param string $nameAttribute
     * @return string
     */
    public function getGroupDnByName($groupName, $nameAttribute = 'cn')
    {
        $groupBaseDn = $this->getRequiredParam(self::PARAM_GROUP_BASE_DN);
        
        return sprintf("%s=%s,%s", $nameAttribute, $groupName, $groupBaseDn);
    }


    /**
     * Sets a proxy user authorization by user DN.
     * 
     * @param string $userDn
     * @throws Exception\GeneralException
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
        
        try {
            $ldapResource = $this->getLdapClient()->getResource();
        } catch (\Exception $e) {
            throw new Exception\GeneralException('Cannot retrieve LDAP resource', null, $e);
        }
        
        if (! ldap_set_option($ldapResource, LDAP_OPT_SERVER_CONTROLS, $serverControls)) {
            throw new Exception\SetOptionException(sprintf("Error setting proxy authentication for DN '%s'", $userDn));
        }
        
        return true;
    }


    /**
     * Searches for the corresponding user node by uid and returns it.
     *
     * @param string $uid
     * @return \Zend\Ldap\Node|null
     */
    public function getUserNodeByUid($uid)
    {
        $userDn = $this->getUserDnByUid($uid);
        
        return $this->getNode($userDn);
    }


    /**
     * Searches for the required DN and returns the corresponding node.
     *
     * @param string $dn
     * @throws Exception\ObjectNotFoundException
     * @return \Zend\Ldap\Node|null
     */
    protected function getNode($dn, $asArray = false)
    {
        try {
            $node = $this->getLdapClient()->getNode($dn);
        } catch (\Exception $e) {
            throw new Exception\ObjectNotFoundException(sprintf("Node '%s' cannot be fetched: [%s] %s", $dn, get_class($e), $e->getMessage()), null, $e);
        }
        
        if ($asArray) {
            return $node->toArray();
        }
        
        return $node;
    }


    /**
     * Appends an attribute value to a node.
     * 
     * @param string $nodeDn
     * @param string $attributeName
     * @param mixed $attributeValue
     */
    protected function appendToNodeAttribute($nodeDn, $attributeName, $attributeValue)
    {
        $node = $this->getNode($nodeDn);
        $node->appendToAttribute($attributeName, $attributeValue);
        $node->update();
    }


    /**
     * Removes an attribute value from a node.
     * 
     * @param string $nodeDn
     * @param string $attributeName
     * @param mixed $attributeValue
     */
    protected function removeFromNodeAttribute($nodeDn, $attributeName, $attributeValue)
    {
        $node = $this->getNode($nodeDn);
        $node->removeFromAttribute($attributeName, $attributeValue);
        $node->update();
    }


    /**
     * Helper method - retrieves a list of user records, which has been represented by user DNs stored in a multivalue
     * attribute in a group node (for example - "uniqueMember").
     * 
     * @param string $groupName
     * @param string $attributeName
     * @return array
     */
    protected function fetchUserRecordsFromGroupAttribute($groupName, $attributeName)
    {
        $groupDn = $this->getGroupDnByName($groupName);
        
        $node = $this->getNode($groupDn);
        $memberDns = $node->getAttribute($attributeName);
        
        $records = array();
        foreach ($memberDns as $userDn) {
            $records[] = $this->getNode($userDn, true);
        }
        
        return $records;
    }


    /**
     * Creates a new group entry.
     * 
     * @param string $groupName
     * @param array $data
     * @return array
     */
    protected function createGroupEntry($groupName, array $data = array())
    {
        $groupEntry = array();
        Attribute::setAttribute($groupEntry, 'cn', $groupName);
        Attribute::setAttribute($groupEntry, 'objectClass', 'top');
        Attribute::setAttribute($groupEntry, 'objectClass', 'groupOfUniqueNames');
        
        return $groupEntry;
    }


    /**
     * Performs an LDAP search.
     * 
     * @param array $params
     * @throws Exception\SearchException
     * @return mixed
     */
    protected function search(array $params)
    {
        try {
            $records = $this->getLdapClient()->search($params);
        } catch (\Exception $e) {
            throw new Exception\SearchException(sprintf("Error while searching with filter '%s' and base DN '%s'", $params['filter'], $params['baseDn']), null, $e);
        }
        
        return $records;
    }
}