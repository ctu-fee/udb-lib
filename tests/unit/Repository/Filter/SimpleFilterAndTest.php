<?php

namespace UdbTest\Domain\Repository\Filter;

use Udb\Domain\Repository\Filter\SimpleFilterAnd;


class SimpleFilterAndTest extends \PHPUnit_Framework_TestCase
{


    public function testConstruct()
    {
        $filterData = array(
            'foo' => 'bar'
        );
        
        $filter = new SimpleFilterAnd($filterData);
        
        $this->assertSame($filterData, $filter->getFilterData());
    }
}