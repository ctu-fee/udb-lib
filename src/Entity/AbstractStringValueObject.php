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
        $this->value = $this->normalizeValue($value);
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


    /**
     * Tries to convert the value to the acceptable internal format.
     * 
     * @param mixed $value
     * @return mixed
     */
    protected function normalizeValue($value)
    {
        return $value;
    }
}