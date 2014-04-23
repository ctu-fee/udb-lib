<?php

namespace Udb\Domain\Repository\Filter;


class SimpleFilterAnd implements FilterInterface
{

    /**
     * @var array
     */
    protected $filterData;


    public function __construct(array $filterData)
    {
        $this->setFilterData($filterData);
    }


    /**
     * @return array
     */
    public function getFilterData()
    {
        return $this->filterData;
    }


    /**
     * @param array $filterData
     */
    public function setFilterData($filterData)
    {
        $this->filterData = $filterData;
    }
}