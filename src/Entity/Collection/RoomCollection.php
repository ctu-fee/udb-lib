<?php

namespace Udb\Domain\Entity\Collection;

use Udb\Domain\Entity\Room;


class RoomCollection extends AbstractStringValueObjectCollection
{


    /**
     * {@inhertidoc}
     * @see \Udb\Domain\Entity\Collection\AbstractCollection::isValid()
     */
    protected function isValid($item)
    {
        return ($item instanceof Room);
    }


    /**
     * Creates a Room value object from the provided value.
     *
     * @param mixed $item
     * @return Room
     */
    protected function createValueObject($item)
    {
        return new Room($item);
    }
}