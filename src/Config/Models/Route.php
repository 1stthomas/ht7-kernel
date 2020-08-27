<?php

namespace Ht7\Kernel\Config\Models;

use \Ht7\Kernel\Routes\Importer;
use \Ht7\Kernel\Config\Models\AbstractConfig;

/**
 * Description of Path
 *
 * @author Thomas Pluess
 */
class Route extends AbstractConfig
{

    public function load()
    {
        $this->values = (new Importer($this->name, $this->path));
    }

}
