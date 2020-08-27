<?php

namespace Ht7\Kernel\Config;

/**
 *
 * @author Thomas Pluess
 */
interface ConfigResolvable
{

    public function get($index, $default = null);

    public function getConfig($type);

    public function has($index);

    public function isDotSeparated($index);
}
