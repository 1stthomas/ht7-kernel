<?php

namespace Ht7\Kernel\Storage\Models;

/**
 * Basic interface for storagable models.
 *
 * @author Thomas Pluess
 */
interface StorageModelable
{

    /**
     * Get the storage type of the present driver.
     *
     * @return  int             The storage type of the present driver.
     * @see                     \Ht7\Kernel\Storage\StorageTypes
     */
    public function getStorageType();
}
