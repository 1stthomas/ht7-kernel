<?php

namespace Ht7\Kernel\Storage\Bridges;

use \Ht7\Kernel\Storage\Bridges\Bridgeable;

/**
 * Interface for select queries on the storage aka reader bridges.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
interface BridgeInable extends Bridgeable
{

    public function getBy();

    public function getById();

    public function getByWhere();

    /**
     * Read the defined data from the storage.
     */
    public function load();
}
