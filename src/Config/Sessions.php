<?php

namespace Ht7\Kernel\Config;

use \Ht7\Kernel\Config\AbstractDottedConfig;
use \Ht7\Kernel\Config\Models\Session as SessionModel;

/**
 * Description of Path
 *
 * @author Thomas Pluess
 */
class Sessions extends AbstractDottedConfig
{

    public function __construct(string $filePathConfigCms, string $filePathConfigApp = '')
    {
        $this->class = SessionModel::class;

        parent::__construct($filePathConfigCms, $filePathConfigApp);
    }

}
