<?php

namespace Udb\Domain\Entity;


class EmailAddress extends AbstractStringValueObject
{


    protected function normalizeValue($value)
    {
        if (! is_string($value)) {
            throw new Exception\InvalidValueException(sprintf("Invalid value '%s', string required", gettype($value)));
        }
        
        /*
         * Very basic validation, just checking for the '@' inside
         */
        if (! preg_match('/^.+@.+$/', $value)) {
            throw new Exception\InvalidValueException(sprintf("Inivalid value format '%s'", $value));
        }
        
        return $value;
    }
}