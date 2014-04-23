<?php

namespace Udb\Domain\User\Storage;

use Udb\Domain\User\Filter\FilterInterface;


interface StorageInterface
{


    /**
     * Returns a single user record.
     * 
     * @param string $uid
     * @return array
     */
    public function fetchUserRecord($uid);


    /**
     * Updates a user record.
     * 
     * @param string $uid
     * @param array $data List of attributes to be updated.
     */
    public function updateUserRecord($uid, array $data);


    /**
     * Returns a list of user records, complying with the provided filter.
     * 
     * @param FilterInterface $filter
     * @return array
     */
    public function fetchUserRecords(FilterInterface $filter = null);
}