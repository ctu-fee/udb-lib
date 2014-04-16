<?php

namespace UdbTest\Domain\User;

use Udb\Domain\User\User;
use Udb\Domain\User\UserHydrator;


class UserHydratorTest extends \PHPUnit_Framework_TestCase
{


    public function testHydrate()
    {
        $hydrator = new UserHydrator();
        
        $user = new User();
        
        $hydrator->hydrate($this->getTestUserData(), $user);
        
        $this->assertSame('testuser', $user->getUsername());
        $this->assertSame(111222, $user->getId());
    }


    protected function getTestUserData()
    {
        return array(
            'dn' => 'uid=testuser,ou=People,o=example.org',
            'cn' => array(
                0 => 'Ing. Ivan Novakov'
            ),
            'cn;lang-cs' => array(
                0 => 'Ing. Ivan Novakov'
            ),
            'cn;lang-en' => array(
                0 => 'Ing. Ivan Novakov'
            ),
            'cvutuid' => array(
                0 => 'testuser'
            ),
            'departmentnumber' => array(
                0 => '13373'
            ),
            'edupersonaffiliation' => array(
                0 => 'member',
                1 => 'employee',
                2 => 'staff'
            ),
            'edupersonprimaryaffiliation' => array(
                0 => 'staff'
            ),
            'edupersonprincipalname' => array(
                0 => 'novakoi@fel.cvut.cz'
            ),
            'employeenumber' => array(
                0 => '111222'
            ),
            'employeetype;lang-cs' => array(
                0 => 'Technickohospodářský pracovník'
            ),
            'employeetype;lang-en' => array(
                0 => 'Technical staff'
            ),
            'entrystatus' => array(
                0 => 'active'
            ),
            'felscopedaffiliation' => array(
                0 => 'employee@13373',
                1 => 'staff@13373',
                2 => 'member@13373'
            ),
            'givenname;lang-cs' => array(
                0 => 'Test'
            ),
            'givenname;lang-en' => array(
                0 => 'Test'
            ),
            'govassignednumber' => array(
                0 => '1234567890'
            ),
            'headdegree' => array(
                0 => 'Ing.'
            ),
            'homephone' => array(
                0 => '+420-950-073-000',
                1 => '+420-950-073-999'
            ),
            'labeleduri' => array(
                0 => 'http://www.example.org Example Homepage',
                2 => 'http://github.com/testuser Github'
            ),
            'mail' => array(
                0 => 'testuser@example.org'
            ),
            'mailalternateaddress' => array(
                0 => 'test.user@example.org',
                1 => 'user.test@example.org'
            ),
            'mailforwardingaddress' => array(
                0 => 'testuser@imap',
                1 => 'testuser@gmail.com'
            ),
            'mobile' => array(
                0 => '+420777888999'
            ),
            'objectclass' => array(
                0 => 'top',
                1 => 'person',
                2 => 'organizationalPerson',
                3 => 'inetOrgPerson',
                4 => 'mailRecipient',
                5 => 'felperson',
                6 => 'feluser',
                7 => 'elections',
                8 => 'eduPerson',
                9 => 'inetuser'
            ),
            'roomnumber' => array(
                0 => 'Praha 6, Nostreet 2, C3-339'
            ),
            'sn;lang-cs' => array(
                0 => 'User'
            ),
            'sn;lang-en' => array(
                0 => 'User'
            ),
            'telephonenumber' => array(
                0 => '+420-22435-2222',
                1 => '+420-22222-3333'
            ),
            'uid' => array(
                0 => 'testuser'
            ),
            'userpassword' => array(
                0 => '{SHA}encoded=='
            )
        );
    }
}