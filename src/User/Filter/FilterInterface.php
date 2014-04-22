<?php

namespace Udb\Domain\User\Filter;


interface FilterInterface
{


    /**
     * Returns the filter data.
     * 
     * @return array
     */
    public function getFilterData();
}