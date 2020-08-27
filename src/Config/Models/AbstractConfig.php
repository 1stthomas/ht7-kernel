<?php

namespace Ht7\Kernel\Config\Models;

use \Ht7\Kernel\Config\Models\ConfigModelable;
use \Ht7\Kernel\Config\Models\ConfigPathType;

/**
 * Description of AbstractConfig
 *
 * @author Thomas Pluess
 */
class AbstractConfig implements ConfigModelable
{

    protected $configPathType;
    protected $filePath;
    protected $values;

    public function __construct(int $configPathType, string $filePath = '')
    {
        $this->setConfigPathType($configPathType);
        $this->setFilePath($filePath);

        if (!empty($filePath)) {
            $this->load();
        }
    }

    public function get($index)
    {
        return $this->getValues()[$index];
    }

    public function getConfigPathType()
    {
        return $this->configPathType;
    }

    public function getFilePath()
    {
        return $this->path;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function has($index)
    {
        return isset($index);
    }

    public function set(string $index, $value)
    {
        $this->values[$index] = $value;
    }

    public function setValues(array $values)
    {
        $this->values = $values;
    }

    protected function load()
    {
        $this->values = include $this->filePath;
    }

    protected function setConfigPathType(int $configPathType)
    {
        $this->configPathType = $configPathType;
    }

    protected function setFilePath(string $filePath)
    {
        $this->filePath = $filePath;
    }

}
