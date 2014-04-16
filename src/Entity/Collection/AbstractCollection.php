<?php

namespace Udb\Domain\Entity\Collection;

use Zend\Stdlib\ArrayObject;
use Zend\Stdlib\Guard\ArrayOrTraversableGuardTrait;


/**
 * Abstract collection.
 */
abstract class AbstractCollection implements CollectionInterface
{

    /**
     * Traits
     */
    use ArrayOrTraversableGuardTrait;
    
    /**
     * @var ArrayObject
     */
    protected $items;


    /**
     * Constructor.
     * 
     * @param array|\Traversable $items
     */
    public function __construct($items = array())
    {
        $this->initItems();
        $this->setItems($items);
    }


    /**
     * Sets all items at once. Removes any existing items.
     * 
     * @param array|\Traversable $items
     */
    public function setItems($items)
    {
        $this->guardForArrayOrTraversable($items);
        $this->initItems();
        
        foreach ($items as $item) {
            $this->append($item);
        }
    }


    /**
     * Appends a single item.
     * 
     * {@inhertidoc}
     * @see \Udb\Domain\Entity\Collection\CollectionInterface::append()
     */
    public function append($item)
    {
        $item = $this->normalizeItem($item);
        $this->check($item);
        $this->items->append($item);
    }


    /**
     * Checks if the item can be added to the collection.
     * 
     * @param mixed $item
     * @throws Exception\InvalidItemException
     */
    public function check($item)
    {
        if (! $this->isValid($item)) {
            $itemType = (is_object($item)) ? get_class($item) : gettype($item);
            throw new Exception\InvalidItemException(sprintf("Invalid item '%s' for collection '%s'", $itemType, get_class($this)));
        }
    }


    /**
     * Returns true, if the item can be added to the collection.
     * 
     * @param mixed $item
     * @return boolean
     */
    public function isValid($item)
    {
        return true;
    }


    /**
     * {@inhertidoc}
     * @see Countable::count()
     */
    public function count()
    {
        return $this->items->count();
    }


    /**
     * {@inhertidoc}
     * @see \Udb\Domain\Entity\Collection\CollectionInterface::get()
     */
    public function get($index, $defaultValue = null)
    {
        if ($this->offsetExists($index)) {
            return $this->items->offsetGet($index);
        }
        
        return $defaultValue;
    }


    /**
     * {@inhertidoc}
     * @see \Udb\Domain\Entity\Collection\CollectionInterface::set()
     */
    public function set($index, $item)
    {
        $item = $this->normalizeItem($item);
        $this->check($item);
        $this->items->offsetSet($index, $item);
    }


    /**
     * {@inhertidoc}
     * @see \Udb\Domain\Entity\Collection\CollectionInterface::toArray()
     */
    public function toArray()
    {
        return $this->items->getArrayCopy();
    }


    /**
     * {@inhertidoc}
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return $this->items->getIterator();
    }


    /**
     * {@inhertidoc}
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($index)
    {
        return $this->items->offsetExists($index);
    }


    /**
     * {@inhertidoc}
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($index)
    {
        return $this->get($index);
    }


    /**
     * {@inhertidoc}
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($index, $item)
    {
        return $this->set($index, $item);
    }


    /**
     * {@inhertidoc}
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($index)
    {
        $this->items->offsetUnset($index);
    }


    /**
     * Tries to transform the item to an acceptable format.
     *
     * @param mixed $item
     */
    protected function normalizeItem($item)
    {
        return $item;
    }


    /**
     * Initializes the items property.
     */
    protected function initItems()
    {
        $this->items = new ArrayObject();
    }
}