<?php

namespace Udb\Domain\Entity;


abstract class AbstractStringValueObject
{

    /**
     * @var string
     */
    protected $value;


    /**
     * Constructor.
     * 
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }


    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }
}