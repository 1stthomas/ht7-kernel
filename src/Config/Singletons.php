<?php

namespace Ht7\Kernel\Config;

use \Ht7\Kernel\Config\AbstractConfig;
use \Ht7\Kernel\Config\Models\Singleton as SingletonModel;

/**
 * Description of Path
 *
 * @author Thomas Pluess
 */
class Singletons extends AbstractConfig
{

    public function __construct(string $filePathConfigCms, string $filePathConfigApp = '')
    {
        $this->class = SingletonModel::class;

        parent::__construct($filePathConfigCms, $filePathConfigApp);
    }

}
