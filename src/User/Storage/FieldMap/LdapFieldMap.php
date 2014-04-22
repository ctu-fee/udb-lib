<?php

namespace Udb\Domain\User\Storage\FieldMap;


class LdapFieldMap implements FieldMapInterface
{

    /**
     * @var array
     */
    protected $fieldMap = array(
        'username' => 'uid',
        'first_name' => 'givenname;lang-cs',
        'last_name' => 'sn;lang-cs'
    );


    /**
     * Constructor.
     * 
     * @param array $fieldMap
     */
    public function __construct(array $fieldMap = null)
    {
        if (null !== $fieldMap) {
            $this->setFieldMap($fieldMap);
        }
    }


    /**
     * @return array
     */
    public function getFieldMap()
    {
        return $this->fieldMap;
    }


    /**
     * @param array $fieldMap
     */
    public function setFieldMap($fieldMap)
    {
        $this->fieldMap = $fieldMap;
    }


    /**
     * {@inhertidoc}
     * @see \Udb\Domain\User\Storage\FieldMap\FieldMapInterface::fieldToStorageField()
     */
    public function fieldToStorageField($fieldName)
    {
        $map = $this->getFieldMap();
        if (isset($map[$fieldName])) {
            return $map[$fieldName];
        }
        
        return null;
    }


    /**
     * {@inhertidoc}
     * @see \Udb\Domain\User\Storage\FieldMap\FieldMapInterface::storageFieldToField()
     */
    public function storageFieldToField($storageFieldName)
    {
        $map = $this->getReversedFieldMap();
        if (isset($map[$storageFieldName])) {
            return $map[$storageFieldName];
        }
        
        return null;
    }


    /**
     * Returns the reversed field mapping.
     * 
     * @return array
     */
    protected function getReversedFieldMap()
    {
        return array_flip($this->fieldMap);
    }
}