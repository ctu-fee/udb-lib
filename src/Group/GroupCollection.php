<?php

namespace Udb\Domain\Group;

use Udb\Domain\Entity\Collection\AbstractCollection;


class GroupCollection extends AbstractCollection
{


    /**
     * {@inheritdoc}
     * @see \Udb\Domain\Entity\Collection\AbstractCollection::isValid()
     */
    protected function isValid($item)
    {
        return ($item instanceof Group);
    }
}