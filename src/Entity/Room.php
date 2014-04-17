<?php

namespace Udb\Domain\Entity;


class Room extends AbstractStringValueObject
{


    protected function normalizeValue($value)
    {
        if (! is_string($value)) {
            throw new Exception\InvalidValueException(sprintf("Invalid value '%s', string required", gettype($value)));
        }
        
        if ('' === $value) {
            throw new Exception\InvalidValueException('Empty string');
        }
        
        return $value;
    }
}