<?php

namespace Ht7\Kernel\Tasks;

use \Ht7\Kernel\Config\Dirs;
use \Ht7\Kernel\Config\Cache;
use \Ht7\Kernel\Config\Config;
use \Ht7\Kernel\Config\ConfigCached;
use \Ht7\Kernel\Config\ConfigDefinitions;
use \Ht7\Kernel\Config\Filenames;
use \Ht7\Kernel\Config\Models\ConfigPathTypes;
use \Ht7\Kernel\Container;
use \Ht7\Kernel\KernelStatus;
use \Ht7\Kernel\Routines\RebuildCache;
use \Ht7\Kernel\Tasks\AbstractTask;

/**
 * Description of FindAppConfigTask
 *
 * @author Thomas Pluess
 */
class ReadConfigTask extends AbstractTask
{

    public function __construct(string $type, Container $container)
    {
        parent::__construct(KernelStatus::READ_CONFIG, $type, $container);

        $this->description = 'Read the config either from cache or from the config files".';

        $this->creates = [
            'instances.config',
            'instances.config_dir',
            'instances.config_filename',
        ];
        $this->needs = [
            'paths.dispatcher',
            'paths.config_cms',
            'paths.config_app',
        ];
    }

    public function process()
    {
        $container = $this->getContainer();

        $dirDispatcher = $container->get('paths.dispatcher');
        $dirConfigCms = $container->get('paths.config_cms');
        $dirConfigApp = $container->get('paths.config_app');

        $fpConfigDefinitions = $dirConfigCms . DIRECTORY_SEPARATOR . 'config_definitions.php';
        $defs = new ConfigDefinitions($fpConfigDefinitions);
        ConfigDefinitions::setInstance($defs);

        $configDirs = new Dirs(
                $dirConfigCms . DIRECTORY_SEPARATOR . $defs->get('dir.file'),
                $dirConfigApp . DIRECTORY_SEPARATOR . $defs->get('dir.file')
        );
        $configDirs->setLocks($defs->get('dir.locks'));
        $configFilenames = new Filenames(
                $dirConfigCms . DIRECTORY_SEPARATOR . $defs->get('filename.file'),
                $dirConfigApp . DIRECTORY_SEPARATOR . $defs->get('filename.file')
        );
        $configFilenames->setLocks($defs->get('filename.locks'));
        $configCache = new Cache(
                $dirConfigCms . DIRECTORY_SEPARATOR . $defs->get('cache.file'),
                $dirConfigApp . DIRECTORY_SEPARATOR . $defs->get('cache.file')
        );

        $fpCacheConfig = $configDirs->get('cache') . DIRECTORY_SEPARATOR;
        $fpCacheConfig .= $configFilenames->get('cache.config');

        $doCache = false;

        if ($configCache->get('category.config.active')) {
            if (file_exists($fpCacheConfig)) {
                echo "<h4>Bin gecacht!</h4>";
                $config = new ConfigCached($fpCacheConfig);
            } else {
                echo "<h4>Bin ned gecacht! Werde cachen.</h4>";
                echo "<p>$fpCacheConfig</p>";
                $config = new Config($dirConfigCms, $dirConfigApp);

                $doCache = true;
            }
        } else {
            echo "<h4>Bin NICHT gecacht!</h4>\n";

            $config = new Config($dirConfigCms, $dirConfigApp);

            if (file_exists($fpCacheConfig) && $config->get('cache.category.config.remove_cache_on_deactivation')) {
                echo "<h4>Lösche config...</h4>";
                unlink($fpCacheConfig);
            }
        }

        if ($config instanceof Config) {
            $config->getConfig('dir')->set('dispatcher', $dirDispatcher, ConfigPathTypes::CMS);

            // todo: aus dem container paths.dispatcher und path.index entfernen.
        }

        $container->addPlain('instances.config', $config);
        $container->addPlain('instances.config_dir', $configDirs);
        $container->addPlain('instances.config_filename', $configFilenames);

        if ($doCache) {
            // The routine needs the config. Therefor it must be set before calling
            // the routine.
            $routine = new RebuildCache();
            $routine->run();
        }

        return $this->getName();
    }

}
