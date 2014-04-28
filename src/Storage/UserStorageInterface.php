<?php

namespace Udb\Domain\Storage;

use Udb\Domain\Repository\Filter\FilterInterface;


interface UserStorageInterface
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


    /**
     * Returns the records of the groups, the user is member of.
     *
     * @param string $uid
     * @return array
    */
    public function fetchUserGroupRecords($uid);
}