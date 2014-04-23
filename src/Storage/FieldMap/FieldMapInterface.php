<?php

namespace Udb\Domain\Storage\FieldMap;


interface FieldMapInterface
{


    /**
     * Returns the corresponding internal storage name of the passed "external" field name.
     * 
     * @param string $fieldName
     * @return string
     */
    public function fieldToStorageField($fieldName);


    /**
     * Returns the corresponding "external" field name of the internal field name.
     *  
     * @param string $storageFieldName
     * @return string
     */
    public function storageFieldToField($storageFieldName);
} 