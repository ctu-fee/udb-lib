<?php

namespace UdbTest\Domain\Util;

use Udb\Domain\Util\Value;


class ValueTest extends \PHPUnit_Framework_TestCase
{


    /**
     * @dataProvider dataProviderValueTypes
     * @param mixed $value
     * @param string $expected
     */
    public function testGetValueType($value, $expected)
    {
        $this->assertSame($expected, Value::getValueType($value));
    }
    
    /*
     * 
     */
    public function dataProviderValueTypes()
    {
        return array(
            array(
                'foo',
                'string'
            ),
            array(
                123,
                'integer'
            ),
            array(
                true,
                'boolean'
            ),
            array(
                array(
                    'foo'
                ),
                'array'
            ),
            array(
                new \stdClass(),
                'stdClass'
            )
        );
    }
}