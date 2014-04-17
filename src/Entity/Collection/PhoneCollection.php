<?php

namespace Udb\Domain\Entity\Collection;

use Udb\Domain\Entity\Phone;


class PhoneCollection extends AbstractStringValueObjectCollection
{


    /**
     * {@inhertidoc}
     * @see \Udb\Domain\Entity\Collection\AbstractCollection::isValid()
     */
    protected function isValid($item)
    {
        return ($item instanceof Phone);
    }


    /**
     * Creates a Phone value object from the provided value.
     * 
     * @param mixed $item
     * @return Phone
     */
    protected function createValueObject($item)
    {
        return new Phone($item);
    }
}