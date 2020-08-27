<?php

namespace Ht7\Kernel\Config\Models;

/**
 *
 * @author Thomas Pluess
 */
interface ConfigModelable
{

    public function get($index);

    public function has($index);
}
