<?php

namespace Udb\Domain\Group;


/**
 * Basic group factory.
 */
class GroupFactory implements GroupFactoryInterface
{


    /**
     * {@inheritdoc}
     * @see \Udb\Domain\Group\GroupFactoryInterface::createGroup()
     */
    public function createGroup()
    {
        return new Group();
    }
}