<?php

namespace Udb\Domain\Group;

use Udb\Domain\Entity\Collection\UidCollection;
use Udb\Domain\Repository\Hydrator\AbstractStorageEntityHydrator;


class GroupHydrator extends AbstractStorageEntityHydrator
{

    /**
     * @var string
     */
    protected $dnRegexp = '/^uid=(\w+),(.+)$/';

    /**
     * @var array
     */
    protected $fieldMap = array(
        'cn' => array(
            'setter' => 'setName',
            'getter' => 'getName'
        ),
        'description' => array(
            'setter' => 'setDescription',
            'getter' => 'getDescription'
        ),
        'mail' => array(
            'setter' => 'setEmail',
            'getter' => 'getEmail'
        ),
        'owner' => array(
            'setter' => 'setOwners',
            'getter' => 'getOwners',
            'multiple' => true,
            'setterTransformMethod' => 'extractUidFromDn',
            'getterTransformMethod' => 'composeDnFromUid'
        )
    );


    /**
     * {@inheritdoc}
     * @see \Udb\Domain\Repository\Hydrator\AbstractStorageEntityHydrator::isValidEntity()
     */
    protected function isValidEntity($group)
    {
        return ($group instanceof Group);
    }


    /**
     * Extracts the UID from the DN.
     * 
     * @param array $values
     * @throws Exception\InvalidGroupDnException
     * @return array
     */
    protected function extractUidFromDn(array $values)
    {
        $uidList = array();
        
        foreach ($values as $dn) {
            if (! preg_match($this->dnRegexp, $dn, $matches)) {
                throw new Exception\InvalidGroupDnException(sprintf("Invalid group DN format '%s'", $dn));
            }
            
            $uidList[] = $matches[1];
        }
        
        return $uidList;
    }


    protected function composeDnFromUid(UidCollection $uids)
    {
        $dnList = array();
        
        foreach ($uids as $uid) {
            // FIXME use parameters to set the DN format
            $dnList[] = sprintf("uid=%s", $uid);
        }
        
        return $dnList;
    }
}