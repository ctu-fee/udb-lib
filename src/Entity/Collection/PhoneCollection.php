<?php

namespace Udb\Domain\Entity\Collection;

use Udb\Domain\Entity\Phone;


class PhoneCollection extends AbstractCollection
{


    public function isValid($item)
    {
        return ($item instanceof Phone);
    }


    public function toPlainArray()
    {
        $values = array();
        foreach ($this->items as $item) {
            $values[] = $item->getValue();
        }
        
        return $values;
    }


    protected function normalizeItem($item)
    {
        try {
            return new Phone($item);
        } catch (\Exception $e) {}
        
        return $item;
    }
}