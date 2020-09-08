<?php

namespace Ht7\Kernel\Config\Categories;

use \Ht7\Kernel\Config\Categories\AbstractDotIndexedConfigCategory;
use \Ht7\Kernel\Config\Models\ConfigModelable;
use \Ht7\Kernel\Config\Models\ConfigPathTypes;

/**
 * Description of AbstractConfig
 *
 * @author Thomas Pluess
 */
class GenericDotIndexedConfigCategory extends AbstractDotIndexedConfigCategory
//class GenericDotIndexedConfigCategory extends AbstractDottedConfig
{

    public function __construct(array $files = [])
    {
        $this->configs = [];

        /* @var $file \Ht7\Kernel\Config\Models\ConfigFileModel */
        foreach ($files as $file) {
            $class = $file->getClass();
//
//            $config = new $class($file);
//            $config = new ($file->getClass())($file);
            $config = new $class($file);

            $this->add($config);
        }
//
//    public function __construct(array $files = [])
//    {
//        $this->configs = [];
//
//        foreach ($files as $class => $file) {
//            $config = new $class($file);
//
//            $this->add($config);
//        }
//        if (!empty($filePathConfigCms)) {
//            $config = new $this->class(
//                    ConfigPathTypes::KERNEL,
//                    $filePathConfigCms
//            );
//            $this->add($config);
//        }
//        if (!empty($filePathConfigApp) && file_exists($filePathConfigApp)) {
//            $config = new $this->class(
//                    ConfigPathTypes::APP,
//                    $filePathConfigApp
//            );
//            $this->add($config);
//        }
    }

    public function initStorageUnits(array $sus)
    {
        ;
    }

}
