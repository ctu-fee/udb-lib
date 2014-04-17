<?php

namespace Udb\Domain\Entity\Collection;


abstract class AbstractStringValueObjectCollection extends AbstractCollection
{


    /**
     * Returns an array of raw string values, instead of objects.
     * 
     * @return array
     */
    public function toPlainArray()
    {
        $values = array();
        foreach ($this->items as $item) {
            $values[] = $item->getValue();
        }
        
        return $values;
    }


    /**
     * {@inhertidoc}
     * @see \Udb\Domain\Entity\Collection\AbstractCollection::normalizeItem()
     */
    protected function normalizeItem($item)
    {
        if ($this->isValid($item)) {
            return $item;
        }
        
        try {
            return $this->createValueObject($item);
        } catch (\Exception $e) {}
        
        return $item;
    }


    /**
     * Creates a value object out of the passed value.
     * 
     * @param mixed $item
     * @return AbstractStringValueObject
     */
    abstract protected function createValueObject($item);
}