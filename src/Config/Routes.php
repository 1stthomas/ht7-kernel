<?php

namespace Ht7\Kernel\Config;

use \Ht7\Kernel\Config\AbstractConfig;
//use \Ht7\CmsSimple\Config\Models\Route;
use \Ht7\Kernel\Routes\Importer;

/**
 * Description of Path
 *
 * @author Thomas Pluess
 */
class Routes extends AbstractConfig
{

    protected $name;

    public function __construct(string $filePathConfigCms, string $filePathConfigApp = '')
    {
//        parent::__construct($filePathConfigCms, $filePathConfigApp);

        echo "<p>";
        echo $filePathConfigApp;
        echo "</p>";
        echo "<p>";
        echo $filePathConfigCms;
        echo "</p>";

        $config = (new Importer($filePathConfigCms))->import();
        $this->add($config);

        if (!empty($filePathConfigApp)) {
            $config = (new Importer($filePathConfigApp))->import();
            $this->add($config);
        }
    }

}
