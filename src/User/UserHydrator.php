<?php

namespace Udb\Domain\User;

use Udb\Domain\Entity\LabelledUrl;
use Udb\Domain\Entity\Collection\LabelledUrlCollection;
use Udb\Domain\Repository\Hydrator\AbstractStorageEntityHydrator;


class UserHydrator extends AbstractStorageEntityHydrator
{

    protected $fieldMap = array(
        'employeenumber' => array(
            'method' => 'setId'
        ),
        'uid' => array(
            'method' => 'setUsername'
        ),
        'cn;lang-cs' => array(
            'method' => 'setFullName'
        ),
        'givenname;lang-cs' => array(
            'method' => 'setFirstName'
        ),
        'sn;lang-cs' => array(
            'method' => 'setLastName'
        ),
        'mail' => array(
            'method' => 'setEmail'
        ),
        'employeetype;lang-cs' => array(
            'method' => 'setEmployeeType'
        ),
        'entrystatus' => array(
            'method' => 'setStatus'
        ),
        'telephonenumber' => array(
            'method' => 'setWorkPhones',
            'multiple' => true
        ),
        'mobile' => array(
            'method' => 'setMobilePhones',
            'multiple' => true
        ),
        'roomnumber' => array(
            'method' => 'setRooms',
            'multiple' => true
        ),
        'departmentnumber' => array(
            'method' => 'setDepartment'
        ),
        'labeleduri' => array(
            'method' => 'setUrls',
            'multiple' => true,
            'transformMethod' => 'transformUrls'
        ),
        'mailforwardingaddress' => array(
            'method' => 'setEmailForwardings',
            'multiple' => true
        ),
        'mailalternateaddress' => array(
            'method' => 'setEmailAlternatives',
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