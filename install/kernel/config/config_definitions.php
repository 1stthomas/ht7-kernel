<?php

// This file is not overridable.

return [
    'cache' => [
        'class' => \Ht7\Kernel\Config\Cache::class,
        'model' => \Ht7\Kernel\Config\Models\Cache::class,
        'file' => 'cache.php',
    ],
    'dir' => [
        'class' => \Ht7\Kernel\Config\Dirs::class,
        'model' => \Ht7\Kernel\Config\Models\Dir::class,
        'file' => 'dir.php',
        'locks' => [
            'root',
            'dispatcher',
            'cache',
            'app.root',
            'app.config',
            'cms.root',
            'cms.config',
            'cms.startup',
        ]
    ],
    'filename' => [
        'class' => \Ht7\Kernel\Config\Filenames::class,
        'model' => \Ht7\Kernel\Config\Models\Filename::class,
        'file' => 'filename.php',
        'locks' => [
            'cms.startup.functions',
            'cache.config',
            'cms.config.app',
            'cms.config.cache',
            'cms.config.cms',
            'cms.config.dir',
            'cms.config.filename',
        ],
    ],
    'kernel' => [
        'class' => \Ht7\Kernel\Config\Kernel::class,
        'model' => \Ht7\Kernel\Config\Models\Kernel::class,
    ],
//    'routes' => [
//        'class' => \Ht7\CmsSimple\Config\Routes::class,
//    ],
//    'session' => [
//        'class' => \Ht7\CmsSimple\Config\Sessions::class,
//        'model' => \Ht7\CmsSimple\Config\Models\Session::class,
//    ],
    'singleton' => [
        'class' => \Ht7\Kernel\Config\Singletons::class,
        'model' => \Ht7\Kernel\Config\Models\Singleton::class,
    ],
];
