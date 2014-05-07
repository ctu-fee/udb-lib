<?php

namespace Udb\Domain\Entity\Collection\Exception;


/**
 * Thrown when collection initialization data are invalid (for example - not array or traversable).
 */
class InvalidCollectionDataException extends \InvalidArgumentException
{
}