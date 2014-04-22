<?php

namespace Udb\Domain\User\Storage\FilterConvertor;

use Udb\Domain\User\Filter\FilterInterface;


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