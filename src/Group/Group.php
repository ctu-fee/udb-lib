<?php

namespace Udb\Domain\Group;

use Udb\Domain\User\User;


/**
 * The user group entity.
 */
class Group
{

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
    protected $email;

    /**
     * @var string
     */
    protected $ownerUid;


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
    public function getOwnerUid()
    {
        return $this->ownerUid;
    }


    /**
     * @param string $ownerUid
     */
    public function setOwnerUid($ownerUid)
    {
        $this->ownerUid = (string) $ownerUid;
    }
}