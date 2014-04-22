<?php

namespace Udb\Domain\User;

use Udb\Domain\Entity\Collection\AbstractCollection;


class UserCollection extends AbstractCollection
{


    /**
     * {@inhertidoc}
     * @see \Udb\Domain\Entity\Collection\AbstractCollection::isValid()
     */
    protected function isValid($item)
    {
        return ($item instanceof User);
    }
}