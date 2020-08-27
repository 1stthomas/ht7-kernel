<?php

namespace Ht7\Kernel\Tasks;

use \Ht7\Kernel\Container;
use \Ht7\Kernel\KernelStatus;
use \Ht7\Kernel\Tasks\AbstractTask;

/**
 * Description of FindAppConfigTask
 *
 * @author Thomas Pluess
 */
class LoadSingletonsTask extends AbstractTask
{

    public function __construct(string $type, Container $container)
    {
        parent::__construct(KernelStatus::LOAD_SINGLETONS, $type, $container);

        $this->description = 'Load all singleton definitions into the cms container.';

        $this->creates = [
            'instances.config_singletons'
        ];
        $this->needs = [
            'instances.cms_container',
            'instances.config',
        ];
    }

    public function process()
    {
        $container = $this->getContainer();
        $cmsContainer = $container->get('instances.cms_container');
        $config = $container->get('instances.config');
        $singletons = $config->getConfig('singleton')->cache();

        foreach ($singletons as $id => $singleton) {
            $cmsContainer->singleton($id, $singleton);
        }

        $cmsContainer->instance('cms/config', $config);

//        echo "<h2>dir.app.config: ";
//        echo $config->get('dir.app.config');
//        echo "</h2>";
//        echo "<h2>dir.root: ";
//        echo $config->get('dir.root');
//        echo "</h2>";
//        echo "<p>aus LoadSingletonTask</p>";
//
//        echo "<pre>";
//        print_r($config->getConfig('dir')->cache());
////        print_r(getC());
//        echo "</pre>";

        return $this->getName();
    }

}
