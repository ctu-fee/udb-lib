<?php

namespace Udb\Domain\User;


class UserFactory implements UserFactoryInterface
{


    public function createUser()
    {
        return new User();
    }
}