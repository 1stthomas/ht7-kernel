<?php

namespace Ht7\CmsSimple\Kernel\Routines\SubRoutines;

use \Ht7\CmsSimple\Export\Files\ArrayExport;
use \Ht7\CmsSimple\Export\Files\JsonExport;
use \Ht7\CmsSimple\Kernel\Container as KernelContainer;
use \Ht7\CmsSimple\Kernel\Routines\AbstractRoutine;

/**
 * Description of RebuildCache
 *
 * @author Thomas Pluess
 */
class RebuildConfigCache extends AbstractRoutine
{

    public function __construct(array $args = [])
    {
        parent::__construct('rebuild_config_cache', $args);
    }

    public function run()
    {
        if (KernelContainer::getInstance()->has('instances.config')) {
            $config = KernelContainer::getInstance()->get('instances.config');
        } else {
            $config = getC()->make('cms/config');
        }

        $fpCache = $config->get('dir.cache') . DIRECTORY_SEPARATOR . $config->get('filename.cache.config');
        $indention = $config->get('cache.category.config.export.indention');

        $data = $config->cache();

        (new ArrayExport($fpCache, $data, $indention))->export();
//        (new JsonExport(rtrim($fpCache, 'php') . 'json', $data, JSON_PRETTY_PRINT))->export();
//
//        echo "<pre>";
//        print_r($config->getConfig('dir')->cache());
////        print_r(getC());
//        echo "</pre>";
    }

}
