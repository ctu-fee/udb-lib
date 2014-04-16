<?php

namespace UdbTest\Domain\User;

use Udb\Domain\User\User;


class UserTest extends \PHPUnit_Framework_TestCase
{


    public function testSettersAndGetters()
    {
        $id = 123;
        $username = 'testuser';
        
        $user = new User();
        $user->setId($id);
        $user->setUsername($username);
        
        $this->assertSame($id, $user->getId());
        $this->assertSame($username, $user->getUsername());
    }
}