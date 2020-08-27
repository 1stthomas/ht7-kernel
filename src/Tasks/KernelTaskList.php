<?php

namespace Ht7\Kernel\Tasks;

use \Ht7\Kernel\Container;
use \Ht7\Kernel\Tasks\TaskList;

/**
 * Description of KernelTaskList
 *
 * @author Thomas Pluess
 */
class KernelTaskList extends TaskList
{

    protected $container;

    public function __construct(Container $container, array $dataCms, array $dataApp = [])
    {
        $this->setContainer($container);

        if (!empty($dataApp)) {
            $data = $this->mergeArray($dataCms, $dataApp);
        } else {
            $data = $dataCms;
        }

        $this->load($data);
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function load(array $data)
    {
        $container = $this->container;

        foreach ($data as $key => $tasks) {
            foreach ($tasks as $task) {
                $this->add((new $task($key, $container)));
            }
        }
    }

    public function mergeArray(array $dataCms, array $dataApp)
    {
        $startup = $dataCms['startup'];

        $arrNew = array_replace_recursive($dataCms, $dataApp);
        $arrNew['startup'] = $startup;

        return $arrNew;
    }

    protected function setContainer(Container $container)
    {
        $this->container = $container;
    }

}
