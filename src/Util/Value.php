<?php

namespace Udb\Domain\Util;


class Value
{


    static public function getValueType($value)
    {
        return is_object($value) ? get_class($value) : gettype($value);
    }
}