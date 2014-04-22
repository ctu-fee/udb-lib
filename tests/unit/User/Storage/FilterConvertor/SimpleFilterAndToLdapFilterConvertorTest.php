<?php

namespace UdbTest\Domain\Storage\FilterConvertor;

use Udb\Domain\User\Storage\FieldMap\LdapFieldMap;
use Udb\Domain\User\Filter\SimpleFilterAnd;
use Udb\Domain\User\Storage\FilterConvertor\SimpleFilterAndToLdapFilterConvertor;


class SimpleFilterAndToLdapFilterConvertorTest extends \PHPUnit_Framework_TestCase
{

    protected $convertor;


    public function setUp()
    {
        $this->convertor = new SimpleFilterAndToLdapFilterConvertor();
    }


    public function testGetImplicitFieldMap()
    {
        $this->assertInstanceOf('Udb\Domain\User\Storage\FieldMap\FieldMapInterface', $this->convertor->getFieldMap());
    }


    public function testSetGetFieldMap()
    {
        $fieldMap = $this->getMock('Udb\Domain\User\Storage\FieldMap\FieldMapInterface');
        $this->convertor->setFieldMap($fieldMap);
        
        $this->assertSame($fieldMap, $this->convertor->getFieldMap());
    }


    public function testConvertWithUnknownField()
    {
        $this->setExpectedException('Udb\Domain\User\Storage\FilterConvertor\Exception\UnknownFieldException', 'Unknown field');
        
        $this->convertor->convert(new SimpleFilterAnd(array(
            'unknown_filter' => 'foo'
        )));
    }


    /**
     * @dataProvider filtersDataProvider
     */
    public function testConvert($filterData, $ldapFilter)
    {
        $fieldMap = new LdapFieldMap(array(
            'username_field' => 'uid_field',
            'first_name_field' => 'givenname_field',
            'last_name_field' => 'sn_field'
        ));
        $this->convertor->setFieldMap($fieldMap);
        
        $filter = new SimpleFilterAnd($filterData);
        
        $this->assertSame($ldapFilter, $this->convertor->convert($filter));
    }


    public function filtersDataProvider()
    {
        return array(
            array(
                'filterData' => array(
                    'username_field' => 'test'
                ),
                'ldapFilter' => '(uid_field=test)'
            ),
            array(
                'filterData' => array(),
                'ldapFilter' => ''
            ),
            array(
                'filterData' => array(
                    'username_field' => 'test*'
                ),
                'ldapFilter' => '(uid_field=test*)'
            ),
            array(
                'filterData' => array(
                    'first_name_field' => 'test',
                    'last_name_field' => 'user'
                ),
                'ldapFilter' => '(&(givenname_field=test)(sn_field=user))'
            ),
            array(
                'filterData' => array(
                    'first_name_field' => 'test*',
                    'last_name_field' => 'user*'
                ),
                'ldapFilter' => '(&(givenname_field=test*)(sn_field=user*))'
            ),
            array(
                'filterData' => array(
                    'username_field' => 'testuser',
                    'first_name_field' => 'test',
                    'last_name_field' => 'user'
                ),
                'ldapFilter' => '(&(uid_field=testuser)(givenname_field=test)(sn_field=user))'
            ),
            array(
                'filterData' => array(
                    'username_field' => 'testuser*',
                    'first_name_field' => 'test*',
                    'last_name_field' => 'user*'
                ),
                'ldapFilter' => '(&(uid_field=testuser*)(givenname_field=test*)(sn_field=user*))'
            ),
            array(
                'filterData' => array(
                    'username_field' => ''
                ),
                'ldapFilter' => ''
            )
        );
    }
}