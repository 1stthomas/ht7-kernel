<?php

namespace Ht7\Kernel\Config\Routines\SubRoutines;

use \Ht7\Kernel\Container as KernelContainer;
use \Ht7\Kernel\Config\Categories\ConfigDefinitionsCategory;
use \Ht7\Kernel\Config\Models\ConfigFileModel;
use \Ht7\Kernel\Config\ConfigPathTypes;
use \Ht7\Kernel\Config\Models\ConfigDefinitionsModel;
use \Ht7\Kernel\Config\Storage\GenericConfigIn;
use \Ht7\Kernel\Config\Storage\GenericConfigOut;
use \Ht7\Kernel\Config\Storage\ConfigDefinitionsStorageUnit;
use \Ht7\Kernel\Utility\Routines\AbstractRoutine;

/**
 * Description of CreateConfig
 *
 * @author Thomas Pluess
 */
class CreateConfigDefinitions extends AbstractRoutine
{

    public function __construct(array $args = [])
    {
        parent::__construct('create_the_config_definitions', $args);
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
        $classDataModel = ConfigDefinitionsModel::class;
        $classIn = GenericConfigIn::class;
        $classOut = GenericConfigOut::class;
        $sus = [];

        foreach ($files as $file) {
            $sus[] = new ConfigDefinitionsStorageUnit(
                    (new $classDataModel()),
                    $file,
                    $classIn,
                    $classOut
            );
        }
        $defsContainer = new ConfigDefinitionsCategory('config_definitions', $sus);

        $container->addPlain('instance.config_definitions', $defsContainer);
    }

}
