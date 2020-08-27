<?php

namespace Ht7\Kernel\Config;

use \Ht7\Kernel\Config\AbstractBase;
use \Ht7\Kernel\Config\AbstractDottedConfig;
use \Ht7\Kernel\Config\ConfigDefinitions;

/**
 * Description of Config
 *
 * @author Thomas Pluess
 */
class Config extends AbstractBase
{

    public function __construct(string $pathConfigCms, string $pathConfigApp)
    {
        $def = ConfigDefinitions::getInstance();

        foreach ($def->cache() as $key => $value) {
            if (empty($value['file'])) {
                $ns = 'config.' . $key;
//                echo "\nns: " . $ns . "\n";
//                echo "<pre>";
//                print_r($this->configs);
//                echo "</pre>\n";

                $fnCms = $this->configs['filename']->get('cms.' . $ns);
                $fnApp = $this->configs['filename']->get('app.' . $ns);
            } else {
                $fnCms = $value['file'];
                $fnApp = $value['file'];
            }

            $fpCms = $pathConfigCms . DIRECTORY_SEPARATOR . $fnCms;
            $fpApp = $pathConfigApp . DIRECTORY_SEPARATOR . $fnApp;

            $this->configs[$key] = new $value['class']($fpCms, $fpApp);
            $this->configs[$key]->setId($key);

            if (!empty($value['locks'])) {
                $this->configs[$key]->setLocks($value['locks']);
            }

            if ($this->configs[$key] instanceof AbstractDottedConfig) {
                $this->dotSeparated[] = $key;
            }
        }
    }

    public function cache()
    {
        $cache = [];

        foreach ($this->configs as $id => $config) {
            $cache[$id] = $config->cache();
        }

        return $cache;
    }

}
