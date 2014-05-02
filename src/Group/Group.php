<?php

namespace Udb\Domain\Group;

use Udb\Domain\Util\Value;
use Udb\Domain\Entity\Exception\InvalidValueException;
use Udb\Domain\Entity\Collection\UidCollection;


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
     * @var UidCollection
     */
    protected $owners;


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
        if (! $owners instanceof UidCollection) {
            if (! is_array($owners)) {
                throw new InvalidValueException(sprintf("Invalid value '%s', expecting array or UidCollection", Value::getValueType($owners)));
            }
            
            $owners = new UidCollection($owners);
        }
        
        $this->owners = $owners;
    }
}