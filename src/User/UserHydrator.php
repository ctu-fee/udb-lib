<?php

namespace Udb\Domain\User;

use Udb\Domain\Entity\Collection\LabelledUrlCollection;
use Udb\Domain\Entity\LabelledUrl;
use Zend\Stdlib\Hydrator\HydratorInterface;


class UserHydrator implements HydratorInterface
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
     * {@inhertidoc}
     * @see \Zend\Stdlib\Extractor\ExtractionInterface::extract()
     */
    public function extract($user)
    {}


    /**
     * {@inhertidoc}
     * @see \Zend\Stdlib\Hydrator\HydrationInterface::hydrate()
     */
    public function hydrate(array $data, $user)
    {
        /* @var $user \Udb\Domain\User\User */
        foreach ($this->fieldMap as $field => $def) {
            if (! isset($data[$field]) || ! is_array($data[$field]) || empty($data[$field])) {
                continue;
            }
            
            if (isset($def['multiple']) && $def['multiple']) {
                $value = $data[$field];
            } else {
                $value = $data[$field][0];
            }
            
            if (isset($def['transformMethod']) && method_exists($this, $def['transformMethod'])) {
                
                $value = call_user_func(array(
                    $this,
                    $def['transformMethod']
                ), $value);
            }
            
            if (isset($data[$field][0])) {
                call_user_func(array(
                    $user,
                    $def['method']
                ), $value);
            }
        }
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