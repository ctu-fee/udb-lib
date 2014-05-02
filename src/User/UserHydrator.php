<?php

namespace Udb\Domain\User;

use Udb\Domain\Entity\Collection\AbstractStringValueObjectCollection;
use Udb\Domain\Entity\Collection\PhoneCollection;
use Udb\Domain\Entity\LabelledUrl;
use Udb\Domain\Entity\Collection\LabelledUrlCollection;
use Udb\Domain\Repository\Hydrator\AbstractStorageEntityHydrator;


class UserHydrator extends AbstractStorageEntityHydrator
{

    protected $fieldMap = array(
        'employeenumber' => array(
            'setter' => 'setId',
            'getter' => 'getId'
        ),
        'uid' => array(
            'setter' => 'setUsername',
            'getter' => 'getUsername'
        ),
        'cn;lang-cs' => array(
            'setter' => 'setFullName',
            'getter' => 'getFullName'
        ),
        'givenname;lang-cs' => array(
            'setter' => 'setFirstName',
            'getter' => 'getFirstName'
        ),
        'sn;lang-cs' => array(
            'setter' => 'setLastName',
            'getter' => 'getLastName'
        ),
        'mail' => array(
            'setter' => 'setEmail',
            'getter' => 'getEmail'
        ),
        'employeetype;lang-cs' => array(
            'setter' => 'setEmployeeType',
            'getter' => 'getEmployeeType'
        ),
        'entrystatus' => array(
            'setter' => 'setStatus',
            'getter' => 'getStatus'
        ),
        'telephonenumber' => array(
            'setter' => 'setWorkPhones',
            'getter' => 'getWorkPhones',
            'multiple' => true,
            'getterTransformMethod' => 'simpleCollectionToArray'
        ),
        'mobile' => array(
            'setter' => 'setMobilePhones',
            'getter' => 'getMobilePhones',
            'multiple' => true,
            'getterTransformMethod' => 'simpleCollectionToArray'
        ),
        'roomnumber' => array(
            'setter' => 'setRooms',
            'getter' => 'getRooms',
            'multiple' => true,
            'getterTransformMethod' => 'simpleCollectionToArray'
        ),
        'departmentnumber' => array(
            'setter' => 'setDepartment',
            'getter' => 'getDepartment'
        ),
        'labeleduri' => array(
            'setter' => 'setUrls',
            'getter' => 'getUrls',
            'multiple' => true,
            'setterTransformMethod' => 'transformUrls',
            'getterTransformMethod' => 'urlsToArray'
        ),
        'mailforwardingaddress' => array(
            'setter' => 'setEmailForwardings',
            'getter' => 'getEmailForwardings',
            'multiple' => true,
            'getterTransformMethod' => 'simpleCollectionToArray'
        ),
        'mailalternateaddress' => array(
            'setter' => 'setEmailAlternatives',
            'getter' => 'getEmailAlternatives',
            'multiple' => true,
            'getterTransformMethod' => 'simpleCollectionToArray'
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


    /**
     * Converts an AbstractStringValueObjectCollection to a plain array.
     * 
     * @param AbstractStringValueObjectCollection $collection
     * @return string[]
     */
    protected function simpleCollectionToArray(AbstractStringValueObjectCollection $collection)
    {
        return $collection->toPlainArray();
    }


    /**
     * Converts a LabelledUrlCollection to a plain array.
     * 
     * @param LabelledUrlCollection $urls
     * @return string[]
     */
    protected function urlsToArray(LabelledUrlCollection $urls)
    {
        $urlData = array();
        foreach ($urls as $url) {
            $urlData[] = sprintf('%s %s', $url->getUrl(), $url->getLabel());
        }
        
        return $urlData;
    }
}