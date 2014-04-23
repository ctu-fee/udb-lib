<?php

namespace Udb\Domain\Repository\Filter;


interface FilterInterface
{


    /**
     * Returns the filter data.
     * 
     * @return array
     */
    public function getFilterData();
}