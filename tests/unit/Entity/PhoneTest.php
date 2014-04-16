<?php

namespace UdbTest\Domain\Entity;

use Udb\Domain\Entity\Phone;


class PhoneTest extends \PHPUnit_Framework_TestCase
{


    /**
     * @dataProvider phoneValues
     */
    public function testConstructor($inputValue, $exception = null, $message = null, $expectedValue = null)
    {
        if ($exception && $message) {
            $this->setExpectedException($exception, $message);
        }
        
        $phone = new Phone($inputValue);
        $this->assertSame($expectedValue, $phone->getValue());
    }


    public function phoneValues()
    {
        return array(
            array(
                '+123-456.789_000',
                null,
                null,
                '+123-456.789_000'
            ),
            array(
                '',
                'Udb\Domain\Entity\Exception\InvalidValueException',
                'Empty string'
            ),
            array(
                array(
                    '123'
                ),
                'Udb\Domain\Entity\Exception\InvalidValueException',
                "Invalid value 'array'"
            ),
            array(
                new Phone('123'),
                'Udb\Domain\Entity\Exception\InvalidValueException',
                "Invalid value 'object'"
            ),
            array(
                '123a',
                'Udb\Domain\Entity\Exception\InvalidValueException',
                'Forbidden characters in value'
            ),
            array(
                '123@#$%^&()<>',
                'Udb\Domain\Entity\Exception\InvalidValueException',
                'Forbidden characters in value'
            )
        );
    }
}