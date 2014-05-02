<?php

namespace Udb\Domain\Group;

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
            'setter' => 'setName'
        ),
        'description' => array(
            'setter' => 'setDescription'
        ),
        'mail' => array(
            'setter' => 'setEmail'
        ),
        'owner' => array(
            'setter' => 'setOwnerUid',
            'setterTransformMethod' => 'extractUidFromDn'
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
     * @param string $dn
     * @throws Exception\InvalidGroupDnException
     * @return string
     */
    protected function extractUidFromDn($dn)
    {
        if (! preg_match($this->dnRegexp, $dn, $matches)) {
            throw new Exception\InvalidGroupDnException(sprintf("Invalid group DN format '%s'", $dn));
        }
        
        $uid = $matches[1];
        
        return $uid;
    }
}