<?php

namespace Ht7\Kernel\Config;

use \Ht7\Kernel\Config\ConfigResolvable;

/**
 * Description of Config
 *
 * @author Thomas Pluess
 */
abstract class AbstractBase implements ConfigResolvable
{

    protected $configs;
    protected $dotSeparated;

    public function get($index, $default = null)
    {
        if ($this->isDotSeparated($index)) {
            $parts = explode('.', $index);
            $type = array_shift($parts);
            $indexNew = implode('.', $parts);
        } else {
            $type = 'route';
            $indexNew = $index;
        }

        $config = $this->getConfig($type);

        return $config->get($indexNew, $default);
    }

    public function getConfig($type)
    {
        return $this->configs[$type];
    }

    public function has($index)
    {
        if ($this->isDotSeparated($index)) {
            $parts = explode('.', $index);
            $type = array_shift($parts);
            $indexNew = implode('.', $parts);
        } else {
            $type = 'route';
            $indexNew = $index;
        }

        $config = $this->getConfig($type);

        return $config->has($indexNew);
    }

    public function isDotSeparated($index)
    {
        foreach ($this->dotSeparated as $type) {
            if (substr($index, 0, strlen($type)) === $type) {
                return true;
            }
        }

        return false;
    }

}
