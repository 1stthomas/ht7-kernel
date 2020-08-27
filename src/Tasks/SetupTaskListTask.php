<?php

namespace Ht7\Kernel\Tasks;

use \Ht7\Kernel\Config\ConfigCached;
use \Ht7\Kernel\Config\Models\ConfigPathTypes;
use \Ht7\Kernel\Container;
use \Ht7\Kernel\KernelStatus;
use \Ht7\Kernel\Tasks\AbstractTask;
use \Ht7\Kernel\Tasks\KernelTaskList;

/**
 * Description of KernelTaskList
 *
 * @author Thomas Pluess
 */
class SetupTaskListTask extends AbstractTask
{

    public function __construct(string $type, Container $container)
    {
        parent::__construct(KernelStatus::SETUP_KERNEL_TASKLIST, $type, $container);

        $this->description = 'Setup the kernel task list.';

        $this->creates = [
            'instances.kernel_tasklist'
        ];
        $this->needs = [
            'instances.config'
        ];
    }

    public function process()
    {
        $container = $this->getContainer();
        $config = $container->get('instances.config');

        if ($config instanceof ConfigCached) {
            $tasksCms = $config->get('kernel.tasks', []);
        } else {
            $tasksCms = $config->getConfig('kernel')->getByConfigPathType('tasks', ConfigPathTypes::CMS);
            $tasksApp = $config->getConfig('kernel')->getByConfigPathType('tasks', ConfigPathTypes::APP);
        }

        if (empty($tasksApp)) {
            $ktL = new KernelTaskList($container, $tasksCms);
        } else {
            $ktL = new KernelTaskList($container, $tasksCms, $tasksApp);
        }

        $container->addPlain('instances.kernel_tasklist', $ktL);

        return $this->getName();
    }

}
