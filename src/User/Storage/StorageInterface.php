<?php

namespace Udb\Domain\User\Storage;


interface StorageInterface
{


    public function fetchUserRecord($uid);


    public function updateUserRecord($uid, array $data);


    public function fetchUserRecords(array $filters = array());
}