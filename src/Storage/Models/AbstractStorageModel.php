<?php

namespace Ht7\Kernel\Storage\Models;

use \Ht7\Kernel\Storage\Models\StorageModelable;

/**
 * Basic implementation of the <code>StorageModelable</code> interface.
 *
 * @author Thomas Pluess
 */
abstract class AbstractStorageModel implements StorageModelable
{

    /**
     * @var     int                     The storage type of the present model.
     *                                  One of the constants defined in
     *                                  <code>\Ht7\Kernel\Storage\StorageTypes</code>.
     */
    protected $storageType;

    /**
     * {@inheritdoc}
     */
    public function getStorageType()
    {
        return $this->storageType;
    }

}
