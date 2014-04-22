<?php

namespace Udb\Domain\User\Storage\FilterConvertor;

use Zend\Ldap;
use Udb\Domain\User\Storage\FieldMap\LdapFieldMap;
use Udb\Domain\User\Storage\FieldMap\FieldMapInterface;
use Udb\Domain\User\Filter\FilterInterface;


/**
 * Converts a SimpleFilterAnd filter to an LDAP search filter (string).
 */
class SimpleFilterAndToLdapFilterConvertor implements FilterConvertorInterface
{

    /**
     * @var FieldMapInterface
     */
    protected $fieldMap;


    /**
     * Constructor.
     * 
     * @param FieldMapInterface $fieldMap
     */
    public function __construct(FieldMapInterface $fieldMap = null)
    {
        if (null !== $fieldMap) {
            $this->setFieldMap($fieldMap);
        }
    }


    /**
     * @return FieldMapInterface
     */
    public function getFieldMap()
    {
        if (! $this->fieldMap instanceof FieldMapInterface) {
            $this->fieldMap = new LdapFieldMap();
        }
        
        return $this->fieldMap;
    }


    /**
     * @param FieldMapInterface $fieldMap
     */
    public function setFieldMap(FieldMapInterface $fieldMap)
    {
        $this->fieldMap = $fieldMap;
    }


    /**
     * {@inhertidoc}
     * @see \Udb\Domain\User\Storage\FilterConvertor\FilterConvertorInterface::convert()
     */
    public function convert(FilterInterface $filter)
    {
        $ldapFilters = array();
        
        foreach ($filter->getFilterData() as $fieldName => $fieldValue) {
            if ('' !== (string) $fieldValue) {
                $ldapFilters[] = $this->convertSingleFilter($fieldName, $fieldValue);
            }
        }
        
        $filtersCount = count($ldapFilters);
        
        if (0 === $filtersCount) {
            return '';
        }
        
        if (1 === $filtersCount) {
            $ldapFilter = $ldapFilters[0];
        } else {
            $ldapFilter = call_user_func_array(array(
                'Zend\Ldap\Filter',
                'andFilter'
            ), $ldapFilters);
        }
        
        return (string) $ldapFilter;
    }


    protected function convertSingleFilter($fieldName, $fieldValue)
    {
        $ldapFieldName = $this->getFieldMap()->fieldToStorageField($fieldName);
        if (null === $ldapFieldName) {
            throw new Exception\UnknownFieldException(sprintf("Unknown field '%s'", $fieldName));
        }
        
        $operation = $this->getComparisonOperation($fieldValue);
        $fieldValue = $this->convertSingleValue($fieldValue);
        
        $ldapFilter = call_user_func(array(
            'Zend\Ldap\Filter',
            $operation
        ), $ldapFieldName, $fieldValue);
        
        return $ldapFilter;
    }


    protected function convertSingleValue($fieldValue)
    {
        $fieldValue = str_replace('*', '', $fieldValue);
        
        return $fieldValue;
    }


    protected function getComparisonOperation($fieldValue)
    {
        if ($fieldValue[0] === '*') {
            return 'ends';
        }
        
        if (mb_substr($fieldValue, - 1) === '*') {
            return 'begins';
        }
        
        return 'equals';
    }
}