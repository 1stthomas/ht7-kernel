<?php

namespace Ht7\Kernel\Config;

use \Ht7\Kernel\Config\AbstractDottedConfig;
use \Ht7\Kernel\Config\Models\Filename as FilenameModel;

/**
 * Description of Path
 *
 * @author Thomas Pluess
 */
class Filenames extends AbstractDottedConfig
{

    public function __construct(string $pathConfigCms, string $pathConfigApp = '')
    {
        $this->class = FilenameModel::class;
        $this->name = 'filename.php';

        parent::__construct($pathConfigCms, $pathConfigApp);
    }

}
