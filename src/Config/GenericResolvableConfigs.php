<?php

namespace Ht7\Kernel\Config;

use \Ht7\Kernel\Config\GenericDottedConfigs;
//use \Ht7\Kernel\Config\AbstractDottedConfig;
use \Ht7\Kernel\Config\Models\ConfigPathTypes;

/**
 * Description of AbstractConfig
 *
 * @author Thomas Pluess
 */
class GenericResolvableConfigs extends GenericDottedConfigs
{

    protected $configsResolved;
    protected $shouldResolve;

    public function __construct(string $class, string $filePathConfigCms = '', string $filePathConfigApp = '')
    {
        $this->configsResolved = [];
        $this->shouldResolve = true;

        parent::__construct($class, $filePathConfigCms, $filePathConfigApp);
    }

    public function cache(int $configPathType = ConfigPathTypes::ALL)
    {
        $configUnresolved = parent::cache($configPathType);
        $model = new $this->class(ConfigPathTypes::CACHE);
        $model->setValues($configUnresolved);
//        echo "<p>class: " . get_class($this) . '</p>';
//        echo "<pre>";
//        print_r($configUnresolved);
//        echo "</pre>";
        $json = json_encode($configUnresolved, JSON_UNESCAPED_UNICODE);
        $jsonResolved = $this->resolve($json, true);

        return json_decode($jsonResolved, true);
    }

    public function get($index, $default = null)
    {
        if (isset($this->configsResolved[$index])) {
            return $this->configsResolved[$index];
        } else {
            $item = parent::get($index, $default);

            if ($this->getShouldResolve()) {
                $itemResolved = $this->resolve($item);

                $this->configsResolved[$index] = $itemResolved;

                return $itemResolved;
            } else {
                return $item;
            }
        }
    }

    public function getByConfigPathType($index, int $configPathType, $default = null)
    {
        $item = parent::getByConfigPathType($index, $configPathType, $default);

        return $this->resolve($item);
    }

    public function getShouldResolve()
    {
        return $this->shouldResolve;
    }

    public function setShouldResolve(bool $shouldResolve)
    {
        $this->shouldResolve = $shouldResolve;
    }

    protected function replacePathPlaceholders($item, $matches, $isCache = false)
    {
        $itemNew = $item;

        foreach ($matches[1] as $match) {
//            if ($isCache) {
//                $replace = $this->getByConfigPathType($match, ConfigPathTypes::CACHE);
////                $replace = $this->getByConfigPathType($replace, ConfigPathTypes::CACHE);
//            } else {
            $replace = $this->get($match, '');
//                $replace = $this->get($replace, '');
//            }
//            $json = json_encode($replace);
//            $json = json_encode(stripslashes($replace));
            $json = json_encode(str_replace('\\\\', '\\', $replace));

            $itemNew = str_replace(
                    '{{' . $match . '}}',
                    trim($json, '"'),
//                    stripslashes(trim($json, '"')),
                    $itemNew
            );
//            $itemNew = str_replace('{{' . $match . '}}', trim(json_encode($replace), '"'), $itemNew);
        }

        return $itemNew;
    }

    protected function resolve($item, $isCache = false)
    {
        if (is_string($item)) {
            $matches = [];

            if (preg_match_all('/{{(.*?)}}/', $item, $matches) > 0) {
                $item = $this->replacePathPlaceholders($item, $matches, $isCache);
            }
        }

        return $item;
    }

}
