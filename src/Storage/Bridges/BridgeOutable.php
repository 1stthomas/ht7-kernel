<?php

namespace Ht7\Kernel\Storage\Bridges;

use \Ht7\Kernel\Storage\Bridges\Bridgeable;

/**
 * Basic interface for writer bridges.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
interface BridgeOutable extends Bridgeable
{

    public function addAfterBy($condition);

    public function addBeforeBy($condition);

    public function append();

    public function replaceWhere($values, $conditions = null);

    /**
     * Replace the whole storage with the present value.
     *
     * @param   mixed   $value
     */
    public function replaceWith($value);
}
