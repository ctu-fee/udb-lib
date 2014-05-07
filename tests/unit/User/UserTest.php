<?php

namespace UdbTest\Domain\User;

use Udb\Domain\Entity\Collection\RoomCollection;
use Udb\Domain\Entity\Collection\EmailAddressCollection;
use Udb\Domain\Entity\Collection\LabelledUrlCollection;
use Udb\Domain\Entity\Collection\PhoneCollection;
use Udb\Domain\User\User;


class UserTest extends \PHPUnit_Framework_TestCase
{


    public function testSettersAndGetters()
    {
        $id = 123;
        $username = 'testuser';
        $firstName = 'Test';
        $lastName = 'User';
        $fullName = 'Dr. Test User';
        $email = 'test.user@example.org';
        $employeeType = 'engineer';
        $status = 'active';
        $workPhones = new PhoneCollection();
        $mobilePhones = new PhoneCollection();
        $rooms = new RoomCollection();
        $department = '11111';
        $urls = new LabelledUrlCollection();
        $emailForwardings = new EmailAddressCollection();
        $emailAlternatives = new EmailAddressCollection();
        
        $user = new User();
        $user->setId($id);
        $user->setUsername($username);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setFullName($fullName);
        $user->setEmail($email);
        $user->setEmployeeType($employeeType);
        $user->setStatus($status);
        $user->setWorkPhones($workPhones);
        $user->setMobilePhones($mobilePhones);
        $user->setRooms($rooms);
        $user->setDepartment($department);
        $user->setUrls($urls);
        $user->setEmailForwardings($emailForwardings);
        $user->setEmailAlternatives($emailAlternatives);
        
        $this->assertSame($id, $user->getId());
        $this->assertSame($username, $user->getUsername());
        $this->assertSame($firstName, $user->getFirstName());
        $this->assertSame($lastName, $user->getLastName());
        $this->assertSame($fullName, $user->getFullName());
        $this->assertSame($email, $user->getEmail());
        $this->assertSame($employeeType, $user->getEmployeeType());
        $this->assertSame($status, $user->getStatus());
        $this->assertSame($workPhones, $user->getWorkPhones());
        $this->assertSame($mobilePhones, $user->getMobilePhones());
        $this->assertSame($rooms, $user->getRooms());
        $this->assertSame($department, $user->getDepartment());
        $this->assertSame($urls, $user->getUrls());
        $this->assertSame($emailForwardings, $user->getEmailForwardings());
        $this->assertSame($emailAlternatives, $user->getEmailAlternatives());
    }


    public function testSetIdWithString()
    {
        $user = new User();
        $user->setId('123');
        $this->assertSame(123, $user->getId());
    }


    public function testSetIdWithNonScalarValue()
    {
        $this->setExpectedException('Udb\Domain\Entity\Exception\InvalidValueException', 'The ID should be a scalar value (integer)');
        
        $user = new User();
        $user->setId(array());
    }


    public function testGetWorkPhonesWithImplicitValue()
    {
        $user = new User();
        
        $phonesCollection = $user->getWorkPhones();
        $this->assertInstanceOf('Udb\Domain\Entity\Collection\PhoneCollection', $phonesCollection);
    }


    public function testSetWorkPhonesWithArray()
    {
        $phones = array(
            '123',
            '456'
        );
        
        $user = new User();
        $user->setWorkPhones($phones);
        
        $phonesCollection = $user->getWorkPhones();
        $this->assertInstanceOf('Udb\Domain\Entity\Collection\PhoneCollection', $phonesCollection);
        $this->assertSame('123', $phonesCollection->get(0)
            ->getValue());
        $this->assertSame('456', $phonesCollection->get(1)
            ->getValue());
    }


    public function testSetWorkPhonesWithInvalidValue()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        $user = new User();
        $user->setWorkPhones('invalid');
    }


    public function testGetMobilePhonesWithImplicitValue()
    {
        $user = new User();
        
        $phonesCollection = $user->getMobilePhones();
        $this->assertInstanceOf('Udb\Domain\Entity\Collection\PhoneCollection', $phonesCollection);
    }


    public function testSetMobilePhonesWithArray()
    {
        $phones = array(
            '123',
            '456'
        );
        
        $user = new User();
        $user->setMobilePhones($phones);
        
        $phonesCollection = $user->getMobilePhones();
        $this->assertInstanceOf('Udb\Domain\Entity\Collection\PhoneCollection', $phonesCollection);
        $this->assertSame($phones, $phonesCollection->toPlainArray());
    }


    public function testSetMobilePhonesWithInvalidValue()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        $user = new User();
        $user->setMobilePhones('invalid');
    }


    public function testGetUrlsWithImplicitValue()
    {
        $user = new User();
        
        $urls = $user->getUrls();
        $this->assertInstanceOf('Udb\Domain\Entity\Collection\LabelledUrlCollection', $urls);
    }


    public function testGetEmailForwardingsWithImplicitValue()
    {
        $user = new User();
        
        $emails = $user->getEmailForwardings();
        $this->assertInstanceOf('Udb\Domain\Entity\Collection\EmailAddressCollection', $emails);
    }


    public function testSetEmailForwardingsWithArray()
    {
        $emails = array(
            'foo@bar',
            'test@example'
        );
        
        $user = new User();
        $user->setEmailForwardings($emails);
        
        $emailsCollection = $user->getEmailForwardings();
        $this->assertInstanceOf('Udb\Domain\Entity\Collection\EmailAddressCollection', $emailsCollection);
        $this->assertSame($emails, $emailsCollection->toPlainArray());
    }


    public function testSetEmailForwardingsWithInvalidValue()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        $user = new User();
        $user->setEmailForwardings('invalid');
    }


    public function testGetEmailAlternativesWithImplicitValue()
    {
        $user = new User();
        
        $emails = $user->getEmailAlternatives();
        $this->assertInstanceOf('Udb\Domain\Entity\Collection\EmailAddressCollection', $emails);
    }


    public function testSetEmailAlternativesWithArray()
    {
        $emails = array(
            'foo@bar',
            'test@example'
        );
        
        $user = new User();
        $user->setEmailAlternatives($emails);
        
        $emailsCollection = $user->getEmailAlternatives();
        $this->assertInstanceOf('Udb\Domain\Entity\Collection\EmailAddressCollection', $emailsCollection);
        $this->assertSame($emails, $emailsCollection->toPlainArray());
    }


    public function testSetEmailAlternativesWithInvalidValue()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        $user = new User();
        $user->setEmailAlternatives('invalid');
    }


    public function testGetRoomsWithImplicitValue()
    {
        $user = new User();
        
        $rooms = $user->getRooms();
        $this->assertInstanceOf('Udb\Domain\Entity\Collection\RoomCollection', $rooms);
    }


    public function testSetRoomsWithArray()
    {
        $rooms = array(
            'A123',
            'B456'
        );
        
        $user = new User();
        $user->setRooms($rooms);
        
        $roomsCollection = $user->getRooms();
        $this->assertInstanceOf('Udb\Domain\Entity\Collection\RoomCollection', $roomsCollection);
        $this->assertSame($rooms, $roomsCollection->toPlainArray());
    }


    public function testSetRoomsWithInvalidValue()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        $user = new User();
        $user->setRooms('invalid');
    }
}