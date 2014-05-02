<?php

namespace Udb\Domain\Entity;


class Uid extends AbstractStringValueObject
{


    public function normalizeValue($value)
    {
        if (! is_string($value)) {
            throw new Exception\InvalidValueException(sprintf("Invalid value '%s', string required", gettype($value)));
        }
        
        if (! preg_match('/^\w+$/', $value)) {
            throw new Exception\InvalidValueException(sprintf("Invalid string value '%s'", $value));
        }
        
        return $value;
    }
}