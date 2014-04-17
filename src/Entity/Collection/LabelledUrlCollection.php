<?php

namespace Udb\Domain\Entity\Collection;

use Udb\Domain\Entity\LabelledUrl;


class LabelledUrlCollection extends AbstractCollection
{


    protected function isValid($item)
    {
        return ($item instanceof LabelledUrl);
    }
}