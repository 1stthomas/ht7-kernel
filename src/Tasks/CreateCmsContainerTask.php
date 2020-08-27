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
class CreateCmsContainerTask extends AbstractTask
{

    public function __construct(string $type, Container $container)
    {
        parent::__construct(KernelStatus::CREATE_CMS_CONTAINER, $type, $container);

        $this->description = 'Create the CMS container. The container class is'
                . ' taken from "kernel.classes.container".';

        $this->creates = [
            'classes.cms_container',
            'instances.cms_container',
        ];
        $this->needs = [
            'instances.config',
        ];
    }

    public function process()
    {
        $container = $this->getContainer();
        $config = $container->get('instances.config');
        $classContainer = $config->get('kernel.classes.container');
        $containerCms = $classContainer::{'getInstance'}();

        $container->addPlain('classes.cms_container', $classContainer);
        $container->addPlain('instances.cms_container', $containerCms);

        return $this->getName();
    }

}
