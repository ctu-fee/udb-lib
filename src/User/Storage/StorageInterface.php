<?php

namespace Udb\Domain\User\Storage;


interface StorageInterface
{


    public function fetchUserRecord($userId);


    public function updateUserRecord($userId, array $data);


    public function fetchUserRecords(array $filters = array());
}