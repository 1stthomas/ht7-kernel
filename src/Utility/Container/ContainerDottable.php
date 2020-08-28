<?php

namespace Ht7\Kernel\Utility\Container;

/**
 *
 * @author Thomas Pluess
 */
interface ContainerDottable
{

    public function add($element);

    public function append($element);

    public function get($index, $default = null);

    public function has($index);
}
