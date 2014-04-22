<?php

namespace UdbTest\Domain\User;

use Udb\Domain\User\UserCollection;


class UserCollectionTest extends \PHPUnit_Framework_TestCase
{


    public function testAppendInvalid()
    {
        $this->setExpectedException('Udb\Domain\Entity\Collection\Exception\InvalidItemException', 'Invalid item');
        
        $users = new UserCollection();
        $users->append(new \stdClass());
    }


    public function testAppend()
    {
        $user = $this->getMock('Udb\Domain\User\User');
        $users = new UserCollection();
        $users->append($user);
        
        $this->assertSame($user, $users->get(0));
    }
}