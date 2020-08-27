<?php

namespace Ht7\Kernel\Config;

use \Ht7\Kernel\Config\AbstractConfig;
use \Ht7\Kernel\Config\Models\ConfigPathTypes;

/**
 * Description of AbstractConfig
 *
 * @author Thomas Pluess
 */
class AbstractDottedConfig extends AbstractConfig
{

    protected function mergeConfigs($configs)
    {
        $arr = [];

        foreach ($configs as $config) {
            $arr = array_replace_recursive($config->getValues(), $arr);
        }

        if (!empty($this->locks)) {
            /* @var $model \Ht7\CmsSimple\Config\Models\AbstractDottedConfig */
            $model = new $this->class(ConfigPathTypes::CACHE);
            $model->setValues($arr);

            foreach ($this->locks as $lock) {
                $model->set($lock, $this->getByConfigPathType($lock, ConfigPathTypes::CMS));
            }

            $arr = $model->getValues();
        }

        return $arr;
    }

}
