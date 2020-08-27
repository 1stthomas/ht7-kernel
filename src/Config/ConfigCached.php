<?php

namespace Ht7\Kernel\Config;

use \Ht7\Kernel\Config\AbstractBase;
use \Ht7\Kernel\Config\AbstractDottedConfig;
use \Ht7\Kernel\Config\ConfigDefinitions;
use \Ht7\Kernel\Config\Models\ConfigPathTypes;

/**
 * Description of Config
 *
 * @author Thomas Pluess
 */
class ConfigCached extends AbstractBase
{

    public function __construct(string $fpCache)
    {
        $def = ConfigDefinitions::getInstance();
        $configsArr = $this->getArrayFromFile($fpCache);

        foreach ($def->cache() as $key => $value) {
            $this->configs[$key] = new $value['class']('');
            $this->configs[$key]->setId($key);

            $model = new $value['model'](ConfigPathTypes::CACHE);
            $model->setValues($configsArr[$key]);
            $this->configs[$key]->add($model);

            if ($this->configs[$key] instanceof AbstractDottedConfig) {
                $this->dotSeparated[] = $key;
            }
        }
    }

    public function getArrayFromFile($fp)
    {
        return include $fp;
    }

}
