<?php

namespace Udb\Domain\Group;


interface GroupFactoryInterface
{


    /**
     * Creates a group entity.
     * 
     * @return \Udb\Domain\Group\Group
     */
    public function createGroup();
}