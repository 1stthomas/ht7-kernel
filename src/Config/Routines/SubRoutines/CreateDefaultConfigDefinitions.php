<?php

namespace Ht7\Kernel\Config\Routines\SubRoutines;

use \Ht7\Kernel\Container as KernelContainer;
use \Ht7\Kernel\Config\Categories\ConfigDefinitionsConfigCategory;
use \Ht7\Kernel\Config\Models\ConfigFileModel;
use \Ht7\Kernel\Config\ConfigPathTypes;
use \Ht7\Kernel\Config\Models\GenericDotIndexedConfigModel;
use \Ht7\Kernel\Config\Storage\GenericConfigIn;
use \Ht7\Kernel\Config\Storage\GenericConfigOut;
use \Ht7\Kernel\Storage\StorageUnit;
use \Ht7\Kernel\Utility\Routines\AbstractRoutine;

/**
 * Description of CreateConfig
 *
 * @author Thomas Pluess
 */
class CreateDefaultConfigDefinitions extends AbstractRoutine
{

    public function __construct(array $args = [])
    {
        parent::__construct('create_the_default_config_definitions', $args);
    }

    public function run()
    {
        $container = KernelContainer::getInstance();
        $dirKernel = $container->get('dir.config_kernel');
        $dirApp = $container->get('dir.config_app');
        $dirOverride = $container->get('dir.config_override');

        $files = [
            new ConfigFileModel(
                    'config_definitions',
                    $dirKernel,
                    ConfigPathTypes::KERNEL
            ),
            new ConfigFileModel(
                    'config_definitions',
                    $dirApp,
                    ConfigPathTypes::APP
            ),
            new ConfigFileModel(
                    'config_definitions',
                    $dirOverride,
                    ConfigPathTypes::OVERRIDE
            ),
        ];
        $classDataModel = GenericDotIndexedConfigModel::class;
        $classIn = GenericConfigIn::class;
        $classOut = GenericConfigOut::class;
        $sus = [];

        foreach ($files as $file) {
            $su = new StorageUnit((new $classDataModel()), $file, $classIn, $classOut);

            $sus[] = $su;
        }
        $defsContainer = new ConfigDefinitionsConfigCategory('config_definitions', $sus);

        echo "<pre>";
        print_r($defsContainer->getSUByConfigPathType(ConfigPathTypes::KERNEL)->getDataModel());
        echo "</pre>";
        echo "<pre>";
        print_r($defsContainer->getSUByConfigPathType(ConfigPathTypes::APP)->getDataModel());
        echo "</pre>";
        echo "<pre>";
        print_r($defsContainer->getSUByConfigPathType(ConfigPathTypes::OVERRIDE));
        echo "</pre>";
    }

}
