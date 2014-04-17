<?php

namespace Udb\Domain\User;

use Zend\Stdlib\Hydrator\HydratorInterface;


class UserHydrator implements HydratorInterface
{


    public function extract($user)
    {}


    public function hydrate(array $data, $user)
    {
        /* @var $user \Udb\Domain\User\User */
        if (isset($data['employeenumber'][0]) && $userId = intval($data['employeenumber'][0])) {
            $user->setId($userId);
        }
        
        if (isset($data['uid'][0])) {
            $user->setUsername($data['uid'][0]);
        }
    }
}