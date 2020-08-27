<?php

namespace Ht7\Kernel\Config;

use \Ht7\Kernel\Config\AbstractDottedConfig;
use \Ht7\Kernel\Config\Models\Cache as CacheModel;

/**
 * Description of Path
 *
 * @author Thomas Pluess
 */
class Cache extends AbstractDottedConfig
{

    public function __construct(string $filePathConfigCms, string $filePathConfigApp = '')
    {
        $this->class = CacheModel::class;

        parent::__construct($filePathConfigCms, $filePathConfigApp);
    }

}
