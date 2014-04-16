<?php

namespace Udb\Domain\Entity;


class Phone extends AbstractStringValueObject
{


    protected function normalizeValue($value)
    {
        if (! is_string($value)) {
            throw new Exception\InvalidValueException(sprintf("Invalid value '%s', string required", gettype($value)));
        }
        
        if ('' === $value) {
            throw new Exception\InvalidValueException('Empty string');
        }
        
        if (! preg_match('/^[0-9-\+\._ ]+$/', $value)) {
            throw new Exception\InvalidValueException(sprintf("Forbidden characters in value '%s'", $value));
        }
        
        return $value;
    }
}