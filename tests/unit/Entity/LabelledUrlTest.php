<?php

namespace UdbTest\Domain\Entity;

use Udb\Domain\Entity\LabelledUrl;


class LabelledUrlTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructor()
    {
        $url = 'http://some';
        $label = 'Some';
        
        $lu = new LabelledUrl($url, $label);
        
        $this->assertSame($url, $lu->getUrl());
        $this->assertSame($label, $lu->getLabel());
    }
}