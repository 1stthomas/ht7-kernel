<?php

namespace Ht7\Kernel;

use \Ht7\Kernel\KernelStatus;
use \Ht7\Kernel\Container;
use \Ht7\Kernel\Tasks\AnalyseEnvironmentTask;
use \Ht7\Kernel\Tasks\AnalyseFolderStructureTask;
use \Ht7\Kernel\Tasks\CreateCmsContainerTask;
use \Ht7\Kernel\Tasks\FixPhpEnvironmentTask;
use \Ht7\Kernel\Tasks\ReadConfigTask;
use \Ht7\Kernel\Tasks\SetupTaskListTask;

/**
 * Description of Dispatcher
 *
 * RunTime?
 * Oder besser "Kernel"?
 *
 * @author Thomas Pluess
 */
class Kernel
{

    protected $dirCaller;
    protected $dirVendor;
    protected $routeList;
    protected $status;
    protected $taskList;

    public function __construct(string $dirCaller, string $dirVendor = '')
    {
        $this->setDirCaller($dirCaller);
        $this->setDirVendor($dirVendor);

        $this->boot();
    }

    public function boot()
    {
        $this->setStatus(KernelStatus::INITIALISED);

        // determine the dir of the index file.
        list($scriptPath) = get_included_files();
        $fileName = basename($scriptPath);
        $dirIndexFile = rtrim($scriptPath, DIRECTORY_SEPARATOR . $fileName);

        $container = Container::getInstance();
        $container->addPlain('paths.index', $dirIndexFile);
//        $container->addPlain('paths.index', getcwd());
        $container->addPlain('paths.dispatcher', $this->getDirCaller());
        if ($this->isInstalled()) {
            $this->setStatus(KernelStatus::RUN_KERNEL_TASKS);

            // Startup tasks. These can not be changed by config.
            $this->setStatus((new FixPhpEnvironmentTask('', $container))->process());
            $this->setStatus((new AnalyseEnvironmentTask('', $container))->process());
            $this->setStatus((new AnalyseFolderStructureTask('', $container))->process());
            $this->setStatus((new ReadConfigTask('', $container))->process());
            $this->setStatus((new CreateCmsContainerTask('', $container))->process());
            $this->setStatus((new SetupTaskListTask('', $container))->process());

            $ktL = $container->get('instances.kernel_tasklist');

            foreach ($ktL as $task) {
                if ($task->getType() !== 'startup') {
                    $this->setStatus($task->process());
                }
            }
        } else {
            // Install the kernel.
            $this->setStatus(KernelStatus::INSTALL_KERNEL);

            $this->setStatus((new FixPhpEnvironmentTask('', $container))->process());
        }

        $this->setStatus('shutdown');
    }

    public function getDirCaller()
    {
        return $this->dirCaller;
    }

    public function getDirVendor()
    {
        return $this->dirVendor;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function isInstalled()
    {
        return stristr($this->getDirCaller(), $this->getDirVendor());

//        return !empty($this->getDirVendor());
    }

    protected function setDirCaller($dirCaller)
    {
        $this->dirCaller = $dirCaller;
    }

    protected function setDirVendor($dirVendor)
    {
        $this->dirVendor = $dirVendor;
    }

    protected function setStatus($status)
    {
        echo "<p>> KERNEL STATUS: $status</p>";
        echo "<hr>";
        $this->status = $status;
    }

}
