<?php

namespace Udb\Domain\Util;

use Udb\Domain\Util\Exception\InvalidArgumentException;
use Udb\Domain\Entity\Collection\CollectionInterface;


/**
 * Provides a method that properly checks and initializes a collection.
 */
trait InitCollectionTrait
{


    /**
     * Returns the required collection or throws an exception in case of invalid input.
     * 
     * @param \Udb\Domain\Entity\Collection\CollectionInterface|array $collection
     * @param string $requiredCollectionClass
     * @throws InvalidArgumentException
     * @return \Udb\Domain\Entity\Collection\CollectionInterface
     */
    protected function initCollection($collection, $requiredCollectionClass)
    {
        if (! class_exists($requiredCollectionClass)) {
            throw new InvalidArgumentException(sprintf("Unknown collection class '%s'", $requiredCollectionClass));
        }
        
        if (! in_array('Udb\Domain\Entity\Collection\CollectionInterface', class_implements($requiredCollectionClass))) {
            throw new InvalidArgumentException(sprintf("Provided collection class '%s' does not implement the collection interface", $requiredCollectionClass));
        }
        
        if ($collection instanceof $requiredCollectionClass) {
            return $collection;
        }
        
        if (is_array($collection)) {
            return new $requiredCollectionClass($collection);
        }
        
        throw new InvalidArgumentException(sprintf("Invalid collection value, expecting array or instance of '%s'", $requiredCollectionClass));
    }
}