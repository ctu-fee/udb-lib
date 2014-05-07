<?php

namespace Udb\Domain\User;

use Udb\Domain\Util\InitCollectionTrait;
use Udb\Domain\Entity\Exception\InvalidValueException;
use Udb\Domain\Entity\Collection\RoomCollection;
use Udb\Domain\Entity\Collection\LabelledUrlCollection;
use Udb\Domain\Entity\Collection\EmailAddressCollection;
use Udb\Domain\Entity\Collection\PhoneCollection;


/**
 * The user entity.
 */
class User
{
    
    use InitCollectionTrait;

    const CLASS_EMAIL_ADDRESS_COLLECTION = 'Udb\Domain\Entity\Collection\EmailAddressCollection';

    const CLASS_PHONE_COLLECTION = 'Udb\Domain\Entity\Collection\PhoneCollection';

    const CLASS_ROOM_COLLECTION = 'Udb\Domain\Entity\Collection\RoomCollection';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $fullName;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $employeeType;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var PhoneCollection
     */
    protected $workPhones;

    /**
     * @var PhoneCollection
     */
    protected $mobilePhones;

    /**
     * @var RoomCollection
     */
    protected $rooms;

    /**
     * @var string
     */
    protected $department;

    /**
     * @var LabelledUrlCollection
     */
    protected $urls;

    /**
     * @var EmailAddressCollection
     */
    protected $emailForwardings;

    /**
     * @var EmailAddressCollection
     */
    protected $emailAlternatives;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param int $id
     */
    public function setId($id)
    {
        if (! is_scalar($id)) {
            throw new InvalidValueException('The ID should be a scalar value (integer)');
        }
        
        $this->id = intval($id);
    }


    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }


    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = (string) $username;
    }


    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }


    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = (string) $firstName;
    }


    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }


    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = (string) $lastName;
    }


    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }


    /**
     * @param string $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = (string) $fullName;
    }


    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = (string) $email;
    }


    /**
     * @return string
     */
    public function getEmployeeType()
    {
        return $this->employeeType;
    }


    /**
     * @param string $employeeType
     */
    public function setEmployeeType($employeeType)
    {
        $this->employeeType = (string) $employeeType;
    }


    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = (string) $status;
    }


    /**
     * @return PhoneCollection
     */
    public function getWorkPhones()
    {
        if (! $this->workPhones instanceof PhoneCollection) {
            $this->workPhones = new PhoneCollection();
        }
        
        return $this->workPhones;
    }


    /**
     * @param PhoneCollection|array $workPhones
     */
    public function setWorkPhones($workPhones)
    {
        $this->workPhones = $this->initCollection($workPhones, self::CLASS_PHONE_COLLECTION);
    }


    /**
     * @return PhoneCollection
     */
    public function getMobilePhones()
    {
        if (! $this->mobilePhones instanceof PhoneCollection) {
            $this->mobilePhones = new PhoneCollection();
        }
        
        return $this->mobilePhones;
    }


    /**
     * @param PhoneCollection|array $mobilePhones
     */
    public function setMobilePhones($mobilePhones)
    {
        $this->mobilePhones = $this->initCollection($mobilePhones, self::CLASS_PHONE_COLLECTION);
    }


    /**
     * @return RoomCollection
     */
    public function getRooms()
    {
        if (! $this->rooms instanceof RoomCollection) {
            $this->rooms = new RoomCollection();
        }
        
        return $this->rooms;
    }


    /**
     * @param RoomCollection|array $room
     */
    public function setRooms($rooms)
    {
        $this->rooms = $this->initCollection($rooms, self::CLASS_ROOM_COLLECTION);
    }


    /**
     * @return string
     */
    public function getDepartment()
    {
        return $this->department;
    }


    /**
     * @param string $department
     */
    public function setDepartment($department)
    {
        $this->department = (string) $department;
    }


    /**
     * @return LabelledUrlCollection
     */
    public function getUrls()
    {
        if (! $this->urls instanceof LabelledUrlCollection) {
            $this->urls = new LabelledUrlCollection();
        }
        
        return $this->urls;
    }


    /**
     * @param LabelledUrlCollection $urls
     */
    public function setUrls(LabelledUrlCollection $urls)
    {
        $this->urls = $urls;
    }


    /**
     * @return EmailAddressCollection
     */
    public function getEmailForwardings()
    {
        if (! $this->emailForwardings instanceof EmailAddressCollection) {
            $this->emailForwardings = new EmailAddressCollection();
        }
        
        return $this->emailForwardings;
    }


    /**
     * @param EmailAddressCollection|array $emailForwardings
     */
    public function setEmailForwardings($emailForwardings)
    {
        $this->emailForwardings = $this->initCollection($emailForwardings, self::CLASS_EMAIL_ADDRESS_COLLECTION);
    }


    /**
     * @return EmailAddressCollection
     */
    public function getEmailAlternatives()
    {
        if (! $this->emailAlternatives instanceof EmailAddressCollection) {
            $this->emailAlternatives = new EmailAddressCollection();
        }
        
        return $this->emailAlternatives;
    }


    /**
     * @param EmailAddressCollection|array $emailAlternatives
     */
    public function setEmailAlternatives($emailAlternatives)
    {
        $this->emailAlternatives = $this->initCollection($emailAlternatives, self::CLASS_EMAIL_ADDRESS_COLLECTION);
    }
}