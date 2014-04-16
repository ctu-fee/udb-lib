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