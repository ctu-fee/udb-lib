<?php

namespace Udb\Domain\Group;

use Udb\Domain\Util\InitCollectionTrait;
use Udb\Domain\Entity\Collection\EmailAddressCollection;
use Udb\Domain\Util\Value;
use Udb\Domain\Entity\Exception\InvalidValueException;
use Udb\Domain\Entity\Collection\UidCollection;


/**
 * The user group entity.
 */
class Group
{
    
    use InitCollectionTrait;

    const CLASS_EMAIL_ADDRESS_COLLECTION = 'Udb\Domain\Entity\Collection\EmailAddressCollection';

    const CLASS_UID_COLLECTION = 'Udb\Domain\Entity\Collection\UidCollection';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $departmentNumber;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var EmailAddressCollection
     */
    protected $emailAlternatives;

    /**
     * @var UidCollection
     */
    protected $owners;

    /**
     * @var boolean
     */
    protected $dynamic;


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string) $name;
    }


    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;
    }


    /**
     * @return string
     */
    public function getDepartmentNumber()
    {
        return $this->departmentNumber;
    }


    /**
     * @param string $departmentNumber
     */
    public function setDepartmentNumber($departmentNumber)
    {
        $this->departmentNumber = $departmentNumber;
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
     * @return EmailAddressCollection
     */
    public function getEmailAlternatives()
    {
        return $this->emailAlternatives;
    }


    /**
     * @param EmailAddressCollection|array $emailAlternatives
     */
    public function setEmailAlternatives($emailAlternatives)
    {
        $this->emailAlternatives = $this->initCollection($emailAlternatives, self::CLASS_EMAIL_ADDRESS_COLLECTION);
    }


    /**
     * @return UidCollection
     */
    public function getOwners()
    {
        return $this->owners;
    }


    /**
     * @param UidCollection|array $owners
     */
    public function setOwners($owners)
    {
        $this->owners = $this->initCollection($owners, self::CLASS_UID_COLLECTION);
    }


    /**
     * @return boolean
     */
    public function isDynamic()
    {
        return $this->dynamic;
    }


    /**
     * @param boolean $dynamic
     */
    public function setDynamic($dynamic)
    {
        $this->dynamic = (boolean) $dynamic;
    }
}