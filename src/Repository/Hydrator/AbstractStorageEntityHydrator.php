<?php

namespace Udb\Domain\Repository\Hydrator;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Udb\Domain\Entity\Exception\InvalidEntityException;


/**
 * Abstract hydrator for hydrating entities with data from the (LDAP) storage.
 */
abstract class AbstractStorageEntityHydrator implements HydratorInterface
{

    /**
     * Defines the mappings between the storage field name and the entity setter.
     * The keys are the storage field names. The values are arrays with specific values:
     *   - "setter" ... the name of the setter method to be used (hydration)
     *   - "getter" ... the name of the getter method to be used (extraction)
     *   - "multiple" ... if true, all values are used, if false or not set - only the first value is used
     *   - "setterTransformMethod" ... the name of the method to be used for custom data transformations 
     *   during hydration, the corresponding value is passed as an argument and the method should return 
     *   the transformed value
     *   - "getterTransformMethod" ...
     * 
     * Example:
     * 
     * array(
     *     'cn' => array(
     *         'setter' => 'setName',
     *         'getter' => 'getName',
     *         'multiple' => false,
     *         'setterTransformMethod' => 'normalizeCn'
     *     ),
     *     
     *     ...
     * )
     * 
     * 
     * @var array
     */
    protected $fieldMap = array();


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
     * @see \Zend\Stdlib\Extractor\ExtractionInterface::extract()
     */
    public function extract($entity)
    {
        $this->checkEntity($entity);
        
        $data = array();
        foreach ($this->getFieldMap() as $field => $def) {
            if (! isset($def['getter'])) {
                continue;
            }
            
            if (! method_exists($entity, $def['getter'])) {
                throw new Exception\UndefinedMethodException(sprintf("Undefined method '%s' for entity '%s' (field: '%s')", $def['getter'], get_class($entity), $field));
            }
            
            $value = call_user_func(array(
                $entity,
                $def['getter']
            ));
            
            if (isset($def['getterTransformMethod']) && method_exists($this, $def['getterTransformMethod'])) {
                $value = call_user_func(array(
                    $this,
                    $def['getterTransformMethod']
                ), $value);
            }
            
            if (null !== $value) {
                if (isset($def['multiple']) && $def['multiple']) {
                    if (! is_array($value)) {
                        $value = array(
                            $value
                        );
                    }
                    
                    $data[$field] = $value;
                } else {
                    $data[$field][0] = $value;
                }
            }
        }
        
        return $data;
    }


    /**
     * {@inhertidoc}
     * @see \Zend\Stdlib\Hydrator\HydrationInterface::hydrate()
     */
    public function hydrate(array $data, $entity)
    {
        $this->checkEntity($entity);
        
        foreach ($this->getFieldMap() as $field => $def) {
            if (! isset($data[$field]) || ! is_array($data[$field]) || empty($data[$field])) {
                continue;
            }
            
            if (! isset($def['setter'])) {
                throw new Exception\UndefinedSetterException(sprintf("Undefined setter for field '%s'", $field));
            }
            
            if (! method_exists($entity, $def['setter'])) {
                throw new Exception\UndefinedMethodException(sprintf("Undefined method '%s' for entity '%s' (field: '%s')", $def['setter'], get_class($entity), $field));
            }
            
            $value = null;
            if (isset($def['multiple']) && $def['multiple']) {
                $value = $data[$field];
            } else {
                $value = $data[$field][0];
            }
            
            if (isset($def['setterTransformMethod']) && method_exists($this, $def['setterTransformMethod'])) {
                $value = call_user_func(array(
                    $this,
                    $def['setterTransformMethod']
                ), $value);
            }
            
            if (null !== $value) {
                call_user_func(array(
                    $entity,
                    $def['setter']
                ), $value);
            }
        }
        
        return $entity;
    }


    /**
     * Checks if the entity is valid and if not - an exception is thrown.
     * 
     * @param mixed $entity
     * @throws InvalidEntityException
     */
    protected function checkEntity($entity)
    {
        if (! $this->isValidEntity($entity)) {
            throw new InvalidEntityException(sprintf("Invalid variable/object '%s'", is_object($entity) ? get_class($entity) : gettype($entity)));
        }
    }


    protected function createExtractionFieldMap()
    {
        $extractionMap = array();
        foreach ($this->getFieldMap() as $def) {}
    }


    /**
     * Returns true, if this hydrator can hydrate/extract the provided entity.
     * 
     * @param mixed $entity
     * @return bool
     */
    abstract protected function isValidEntity($entity);
}