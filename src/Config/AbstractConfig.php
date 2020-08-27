<?php

namespace Ht7\Kernel\Config;

use \Ht7\Kernel\Config\Models\ConfigModelable;
use \Ht7\Kernel\Config\Models\ConfigPathTypes;

/**
 * Description of AbstractConfig
 *
 * @author Thomas Pluess
 */
class AbstractConfig
{

    protected $configs;
    protected $class;
    protected $id;
    protected $locks;

    public function __construct(string $filePathConfigCms = '', string $filePathConfigApp = '')
    {
        $this->configs = [];

        if (!empty($filePathConfigCms)) {
            $config = new $this->class(
                    ConfigPathTypes::CMS,
                    $filePathConfigCms
            );
            $this->add($config);
        }

        if (!empty($filePathConfigApp) && file_exists($filePathConfigApp)) {
            $config = new $this->class(
                    ConfigPathTypes::APP,
                    $filePathConfigApp
            );
            $this->add($config);
        }
    }

    public function add(ConfigModelable $config)
    {
        array_unshift($this->configs, $config);
    }

    public function append(ConfigModelable $config)
    {
        $this->configs[] = $config;
    }

    public function cache(int $configPathType = ConfigPathTypes::ALL)
    {
        if ($configPathType === ConfigPathTypes::ALL) {
            return $this->mergeConfigs($this->configs);
        } else {
            return $this->cacheByType($configPathType);
        }
    }

    public function cacheByType(int $configPathType)
    {
        $configs = [];

        foreach ($this->configs as $config) {
            if ($config->getConfigPathType() === $configPathType) {
                $configs[] = $config;
            }
        }

        if (count($configs) === 0) {
            return [];
        } elseif (count($configs) === 1) {
            return $configs[0];
        } else {
            return $this->mergeConfigs($configs);
        }
    }

    public function get($index, $default = null)
    {
        if ($this->isLocked($index)) {
            return $this->getByConfigPathType($index, ConfigPathTypes::CMS, $default);
        }

        foreach ($this->configs as $config) {
            if ($config->has($index)) {
                return $config->get($index);
            }
        }

        return $default;
    }

    public function getByConfigPathType($index, int $configPathType, $default = null)
    {
        foreach ($this->configs as $config) {
            if ($config->getConfigPathType() === $configPathType && $config->has($index)) {
                return $config->get($index);
            }
        }

        return $default;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLocks()
    {
        return $this->locks;
    }

    public function has($index)
    {
        foreach ($this->configs as $config) {
            if ($config->has($index)) {
                return true;
            }
        }

        return false;
    }

    public function isLocked($index)
    {
        return !empty($this->locks) && in_array($index, $this->locks);
    }

    public function rGet(string $index, $default = null)
    {
        $this->configs = array_reverse($this->configs);
        $item = $this->get($index, $default);
        $this->configs = array_reverse($this->configs);

        return $item;
    }

    public function set(string $index, $value, int $configPathType = ConfigPathTypes::CACHE)
    {
        foreach ($this->configs as $config) {
            if ($config->getConfigPathType() === $configPathType) {
                $config->set($index, $value);
            }
        }
    }

    public function setId(string $id)
    {
        $this->id = $id;
    }

    public function setLocks(array $locks)
    {
        $this->locks = $locks;
    }

    protected function mergeConfigs($configs)
    {
        $arr = [];

        foreach ($configs as $config) {
            $arr = array_merge($config->getValues(), $arr);
        }

        return $arr;
    }

}
