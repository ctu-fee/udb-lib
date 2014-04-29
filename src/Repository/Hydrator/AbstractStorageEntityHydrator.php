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
     *   - "method" ... the name of the setter method to be used
     *   - "multiple" ... if true, all values are used, if false or not set - only the first value is used
     *   - "transformMethod" .. the name of the method to be used for custom data transformations, the
     *   corresponding value is passed as an argument and the method should return the transformed value
     * 
     * Example:
     * 
     * array(
     *     'cn' => array(
     *         'method' => 'setName',
     *         'multiple' => false,
     *         'transformMethod' => 'normalizeCn'
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
     * {@inhertidoc}
     * @see \Zend\Stdlib\Extractor\ExtractionInterface::extract()
     */
    public function extract($user)
    {}


    /**
     * {@inhertidoc}
     * @see \Zend\Stdlib\Hydrator\HydrationInterface::hydrate()
     */
    public function hydrate(array $data, $entity)
    {
        $this->checkEntity($entity);
        
        foreach ($this->fieldMap as $field => $def) {
            if (! isset($data[$field]) || ! is_array($data[$field]) || empty($data[$field])) {
                continue;
            }
            
            if (isset($def['multiple']) && $def['multiple']) {
                $value = $data[$field];
            } else {
                $value = $data[$field][0];
            }
            
            if (isset($def['transformMethod']) && method_exists($this, $def['transformMethod'])) {
                
                $value = call_user_func(array(
                    $this,
                    $def['transformMethod']
                ), $value);
            }
            
            if (isset($data[$field][0])) {
                call_user_func(array(
                    $entity,
                    $def['method']
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


    /**
     * Returns true, if this hydrator can hydrate/extract the provided entity.
     * 
     * @param mixed $entity
     * @return bool
     */
    abstract protected function isValidEntity($entity);
}