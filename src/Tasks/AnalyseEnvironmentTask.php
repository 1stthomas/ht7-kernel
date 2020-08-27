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
class AnalyseEnvironmentTask extends AbstractTask
{

    public function __construct(string $type, Container $container)
    {
        parent::__construct(KernelStatus::ANALYSE_ENVIRONMENT, $type, $container);

        $this->description = 'Analyse the runtime environment.';

        $this->creates = [
            ''
        ];
        $this->needs = [
//            'instances.config_dir',
//            'instances.config_filename',
        ];
    }

    public function process()
    {
//        $container = $this->getContainer();
//        $configDir = $container->get('instances.config_dir');
//        $configFilename = $container->get('instances.config_filename');

        echo "\n\n";
        echo "sapi name: \n";
        echo php_sapi_name();
        echo "\n\n";

        return $this->getName();
    }

}
