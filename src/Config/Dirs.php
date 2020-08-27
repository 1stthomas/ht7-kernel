<?php

namespace Ht7\CmsSimple\Config;

use \Ht7\Kernel\Config\AbstractResolvableConfig;
use \Ht7\Kernel\Config\Models\ConfigPathTypes;
use \Ht7\Kernel\Config\Models\Dir as DirModel;

/**
 * Description of Path
 *
 * @author Thomas Pluess
 */
class Dirs extends AbstractResolvableConfig
{

    public function __construct(string $pathConfigCms, string $pathConfigApp = '')
    {
        $this->class = DirModel::class;

        parent::__construct($pathConfigCms, $pathConfigApp);
    }

}
