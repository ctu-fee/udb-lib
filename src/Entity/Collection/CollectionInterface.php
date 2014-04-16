<?php

namespace Udb\Domain\Entity\Collection;


interface CollectionInterface extends \Countable, \IteratorAggregate, \ArrayAccess
{


    public function append($item);


    public function get($index, $defaultValue = null);


    public function set($index, $item);


    public function toArray();
}