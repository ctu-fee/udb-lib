<?php

namespace Udb\Domain\Storage\FilterConvertor;

use Udb\Domain\Repository\Filter\FilterInterface;


interface FilterConvertorInterface
{


    /**
     * Converts a user filter into format understandable by the corresponding storage.
     * 
     * @param FilterInterface $filter
     * @return mixed
     */
    public function convert(FilterInterface $filter);
}