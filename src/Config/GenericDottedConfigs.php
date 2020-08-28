<?php

namespace Ht7\Kernel\Config;

use \Ht7\Kernel\Utility\Container\DottedContainer;

/**
 * Description of Path
 *
 * @author Thomas Pluess
 */
class GenericDottedConfigs extends DottedContainer
{

    protected $locks;

    public function __construct(string $class, string $filePathConfigCms, string $filePathConfigApp = '')
    {
        parent::__construct($class);

        if (!empty($filePathConfigCms)) {
            $element = new $class(
                    ConfigPathTypes::KERNEL,
                    $filePathConfigCms
            );
            $this->add($element);
        }

        if (!empty($filePathConfigApp) && file_exists($filePathConfigApp)) {
            $element = new $class(
                    ConfigPathTypes::APP,
                    $filePathConfigApp
            );
            $this->add($element);
        }
    }

    public function cache()
    {
        return $this->mergeElements($this->elements);
    }

    public function cacheByType(int $configPathType)
    {
        $elements = [];

        foreach ($this->elements as $element) {
            if ($element->getConfigPathType() === $configPathType) {
                $elements[] = $element;
            }
        }

        if (count($elements) === 0) {
            return [];
        } elseif (count($elements) === 1) {
            return $elements[0];
        } else {
            return $this->mergeElements($elements);
        }
    }

    public function get($index, $default = null)
    {
        if ($this->isLocked($index)) {
            return $this->getByConfigPathType($index, ConfigPathTypes::KERNEL, $default);
        }

        return parent::get($index, $default);
    }

    public function getByConfigPathType($index, int $configPathType, $default = null)
    {
        foreach ($this->elements as $element) {
            if ($element->getConfigPathType() === $configPathType && $element->has($index)) {
                return $element->get($index);
            }
        }

        return $default;
    }

    public function getLocks()
    {
        return $this->locks;
    }

    public function isLocked($index)
    {
        return !empty($this->locks) && in_array($index, $this->locks);
    }

    public function set(string $index, $value, int $elementPathType = ConfigPathTypes::CACHE)
    {
        foreach ($this->elements as $element) {
            if ($element->getConfigPathType() === $elementPathType) {
                $element->set($index, $value);
            }
        }
    }

    public function setLocks(array $locks)
    {
        $this->locks = $locks;
    }

    protected function mergeElements($elements)
    {
        $arr = [];

        foreach ($elements as $element) {
            $arr = array_merge($element->getValues(), $arr);
        }

        return $arr;
    }

}
