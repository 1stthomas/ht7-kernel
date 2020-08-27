<?php

namespace Ht7\Kernel\Config;

use \Ht7\Kernel\Config\AbstractDottedConfig;
use \Ht7\Kernel\Config\Models\ConfigDefinitions as ConfigDefinitionsModel;

/**
 * Description of Path
 *
 * @author Thomas Pluess
 */
class ConfigDefinitions extends AbstractDottedConfig
{

    protected static $instance;

    public function __construct(string $pathConfigCms = '')
    {
        $this->class = ConfigDefinitionsModel::class;

        parent::__construct($pathConfigCms);
    }

    public static function getInstance()
    {
        return self::$instance;
    }

    public static function setInstance(ConfigDefinitions $cd)
    {
        self::$instance = $cd;
    }

}
