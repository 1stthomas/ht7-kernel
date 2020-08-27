<?php

namespace Ht7\Kernel\Config;

use \Ht7\Kernel\Config\AbstractDottedConfig;
use \Ht7\Kernel\Config\Models\Kernel as KernelModel;

/**
 * Description of Path
 *
 * @author Thomas Pluess
 */
class Kernel extends AbstractDottedConfig
{

    public function __construct(string $filePathConfigCms, string $filePathConfigApp = '')
    {
        $this->class = KernelModel::class;

        parent::__construct($filePathConfigCms, $filePathConfigApp);
    }

}
