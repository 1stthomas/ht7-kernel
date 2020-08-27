<?php

namespace Ht7\Kernel\Tasks;

use \Ht7\Kernel\Container;
use \Ht7\Kernel\KernelStatus;
use \Ht7\Kernel\Tasks\AbstractTask;
use \Ht7\Kernel\Config\Models\ConfigPathTypes;

/**
 * Description of FindAppConfigTask
 *
 * @author Thomas Pluess
 */
class LoadFunctionsTask extends AbstractTask
{

    public function __construct(string $type, Container $container)
    {
        parent::__construct(KernelStatus::LOAD_FUNCTIONS, $type, $container);

        $this->description = 'Load the global functions.';

        $this->creates = [];
        $this->needs = [
            'instances.config_dir',
            'instances.config_filename',
        ];
    }

    public function process()
    {
        $container = $this->getContainer();
        $configDir = $container->get('instances.config_dir');
        $configFilename = $container->get('instances.config_filename');

        $pathCmsStartup = $configDir->getByConfigPathType('cms.startup', ConfigPathTypes::CMS);
        $pathAppStartup = $configDir->get('app.startup');
        $filenameCmsStartup = $configFilename->getByConfigPathType('cms.startup.functions', ConfigPathTypes::CMS);
        $filenameAppStartup = $configFilename->get('app.startup.functions');

        require $pathCmsStartup . DIRECTORY_SEPARATOR . $filenameCmsStartup;

        if (file_exists($pathAppStartup . DIRECTORY_SEPARATOR . $filenameAppStartup)) {
            require $pathAppStartup . DIRECTORY_SEPARATOR . $filenameAppStartup;
        }

        return $this->getName();
    }

}
