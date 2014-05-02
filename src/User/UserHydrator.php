<?php

namespace Udb\Domain\User;

use Udb\Domain\Entity\LabelledUrl;
use Udb\Domain\Entity\Collection\LabelledUrlCollection;
use Udb\Domain\Repository\Hydrator\AbstractStorageEntityHydrator;


class UserHydrator extends AbstractStorageEntityHydrator
{

    protected $fieldMap = array(
        'employeenumber' => array(
            'setter' => 'setId'
        ),
        'uid' => array(
            'setter' => 'setUsername'
        ),
        'cn;lang-cs' => array(
            'setter' => 'setFullName'
        ),
        'givenname;lang-cs' => array(
            'setter' => 'setFirstName'
        ),
        'sn;lang-cs' => array(
            'setter' => 'setLastName'
        ),
        'mail' => array(
            'setter' => 'setEmail'
        ),
        'employeetype;lang-cs' => array(
            'setter' => 'setEmployeeType'
        ),
        'entrystatus' => array(
            'setter' => 'setStatus'
        ),
        'telephonenumber' => array(
            'setter' => 'setWorkPhones',
            'multiple' => true
        ),
        'mobile' => array(
            'setter' => 'setMobilePhones',
            'multiple' => true
        ),
        'roomnumber' => array(
            'setter' => 'setRooms',
            'multiple' => true
        ),
        'departmentnumber' => array(
            'setter' => 'setDepartment'
        ),
        'labeleduri' => array(
            'setter' => 'setUrls',
            'multiple' => true,
            'transformMethod' => 'transformUrls'
        ),
        'mailforwardingaddress' => array(
            'setter' => 'setEmailForwardings',
            'multiple' => true
        ),
        'mailalternateaddress' => array(
            'setter' => 'setEmailAlternatives',
            'multiple' => true
        )
    );


    /**
     * {@inheritdoc}
     * @see \Udb\Domain\Repository\Hydrator\AbstractStorageEntityHydrator::isValidEntity()
     */
    protected function isValidEntity($user)
    {
        return ($user instanceof User);
    }


    /**
     * Transforms an array of URLs with labels into a LabelledUrlCollection.
     * 
     * @param array $urlStrings
     * @return LabelledUrlCollection
     */
    protected function transformUrls($urlStrings)
    {
        $urlCollection = new LabelledUrlCollection();
        
        foreach ($urlStrings as $urlString) {
            $parts = explode(' ', $urlString, 2);
            if (count($parts) !== 2) {
                continue;
            }
            
            $url = new LabelledUrl(trim($parts[0]), trim($parts[1]));
            $urlCollection->append($url);
        }
        
        return $urlCollection;
    }
}