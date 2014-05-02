<?php

namespace Udb\Domain\Entity\Collection;

use Udb\Domain\Entity\Uid;


class UidCollection extends AbstractStringValueObjectCollection
{


    /**
     * {@inhertidoc}
     * @see \Udb\Domain\Entity\Collection\AbstractCollection::isValid()
     */
    protected function isValid($item)
    {
        return ($item instanceof Uid);
    }


    /**
     * Creates a Phone value object from the provided value.
     *
     * @see \Udb\Domain\Entity\Collection\AbstractStringValueObjectCollection::createValueObject()
     * @param mixed $item
     * @return Uid
     */
    protected function createValueObject($item)
    {
        return new Uid($item);
    }
}