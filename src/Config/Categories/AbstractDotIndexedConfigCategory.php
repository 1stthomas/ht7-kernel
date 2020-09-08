<?php

namespace Ht7\Kernel\Config\Categories;

use \Ht7\Kernel\Config\Categories\AbstractIndexedConfigCategory;
use \Ht7\Kernel\Config\Models\ConfigPathTypes;

/**
 * Description of AbstractConfig
 *
 * @author Thomas Pluess
 */
abstract class AbstractDotIndexedConfigCategory extends AbstractIndexedConfigCategory
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
                $model->set($lock, $this->getByConfigPathType($lock, ConfigPathTypes::KERNEL));
            }

            $arr = $model->getValues();
        }

        return $arr;
    }

}
