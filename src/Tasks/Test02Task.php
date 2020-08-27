<?php

namespace Ht7\Kernel\Tasks;

use \Ht7\Kernel\Container;
use \Ht7\Kernel\Tasks\AbstractTask;

/**
 * Description of FindAppConfigTask
 *
 * @author Thomas Pluess
 */
class Test02Task extends AbstractTask
{

    public function __construct(string $type, Container $container)
    {
        parent::__construct('test_01', $type, $container);
    }

    public function process()
    {
        echo "<h2>";
        echo "=====> aus Test2!!!";
        echo "</h2>";

        return $this->getName();
    }

}
