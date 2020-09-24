<?php

namespace Ht7\Kernel\Config;

use \Ht7\Base\Lists\ItemList;
use \Ht7\Base\Lists\Hashable;
use \Ht7\Kernel\Config\Utility\CanConfigPathTypeHash;

/**
 * The <code>LockListType</code> class holds the locks of a specific config
 * path type.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class LockListType extends ItemList implements Hashable
{

    use CanConfigPathTypeHash;

    /**
     * Create an instance of the <code>LockListType</code> class.
     *
     * @param   string      $type       The config path type of the present locks.
     * @param   array       $data       An indexed array of locked index'.
     */
    public function __construct(string $type, array $data = [])
    {
        $this->configPathType = $type;

        parent::__construct($data);
    }

}
