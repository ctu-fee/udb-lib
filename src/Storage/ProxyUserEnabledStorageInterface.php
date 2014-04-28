<?php

namespace Udb\Domain\Storage;


/**
 * Storage that allows proxy user authorization.
 */
interface ProxyUserEnabledStorageInterface
{


    /**
     * Returns true, if the storage supports proxy authentication.
     *
     * @return boolean
     */
    public function supportsProxyAuthentication();


    /**
     * Sets a proxy user authorization by UID.
     *
     * @param string $uid
     * @return boolean
    */
    public function setProxyUserByUid($uid);
}