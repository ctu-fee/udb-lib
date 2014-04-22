<?php

namespace Udb\Domain\User\Storage;

use Udb\Domain\User\Filter\FilterInterface;


interface StorageInterface
{


    public function fetchUserRecord($uid);


    public function updateUserRecord($uid, array $data);


    public function fetchUserRecords(FilterInterface $filter = null);
}