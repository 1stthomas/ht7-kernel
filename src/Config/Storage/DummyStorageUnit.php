<?php

namespace Ht7\Kernel\Config\Storage;

use \Ht7\Base\Lists\Hashable;

/**
 * The <code>DummyStorageUnit</code> class is needed to represent the loading
 * sequence in an empty <code>StorageUnitList</code> instance.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class DummyStorageUnit implements Hashable
{

    /**
     * @var     string          The config path type of the present dummy.
     */
    protected $configPathType;

    /**
     * Create an instance of the <code>DummyStorageUnit</code> class.
     *
     * @param   string  $configPathType     The config path type
     */
    public function __construct(string $configPathType)
    {
        $this->configPathType = $configPathType;
    }

    /**
     * {@inheritdoc}
     *
     * @return  string                      The config path type.
     */
    public function getHash()
    {
        return $this->configPathType;
    }

}
