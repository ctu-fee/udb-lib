<?php

namespace UdbTest\Domain\Entity\Collection;

use Udb\Domain\Entity\LabelledUrl;
use Udb\Domain\Entity\Collection\LabelledUrlCollection;


class LabelledUrlCollectionTest extends \PHPUnit_Framework_TestCase
{

    protected $col;


    public function setUp()
    {
        $this->col = new LabelledUrlCollection();
    }


    public function testAppendValid()
    {
        $lu = new LabelledUrl('http://some', 'Foo');
        
        $this->col->append($lu);
        $this->assertSame($lu, $this->col->get(0));
    }


    public function testAppendInvalid()
    {
        $this->setExpectedException('Udb\Domain\Entity\Collection\Exception\InvalidItemException', 'Invalid item');
        
        $this->col->append(new \stdClass());
    }
}