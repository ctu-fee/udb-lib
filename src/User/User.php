<?php

namespace Udb\Domain\User;


/**
 * The user entity.
 */
class User
{

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

    protected $workPhone;

    protected $mobilePhone;

    /**
     * @var string
     */
    protected $room;

    /**
     * @var string
     */
    protected $department;

    protected $homepage;

    protected $emailForwarding;

    protected $emailAlternative;


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
        $this->id = $id;
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
        $this->username = $username;
    }
}