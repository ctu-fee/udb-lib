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
        if ($this->items->offsetExists($index)) {
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
        $this->check($item);
        $this->items->offsetSet($index, $item);
    }


    /**
     * Initializes the items property.
     */
    protected function initItems()
    {
        $this->items = new ArrayObject();
    }
}