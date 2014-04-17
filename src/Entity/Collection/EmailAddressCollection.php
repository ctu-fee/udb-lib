<?php

namespace Udb\Domain\Entity\Collection;

use Udb\Domain\Entity\EmailAddress;


class EmailAddressCollection extends AbstractStringValueObjectCollection
{


    /**
     * {@inhertidoc}
     * @see \Udb\Domain\Entity\Collection\AbstractCollection::isValid()
     */
    protected function isValid($item)
    {
        return ($item instanceof EmailAddress);
    }


    /**
     * Creates an EmailAddress value object from the provided value.
     * 
     * @param unknown_type $item
     * @return EmailAddress
     */
    protected function createValueObject($item)
    {
        return new EmailAddress($item);
    }
}