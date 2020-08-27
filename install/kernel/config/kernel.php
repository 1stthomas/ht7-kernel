<?php

return [
    'classes' => [
        'container' => \Ht7\CmsSimple\Utility\Container\ContainerSimple::class,
//        'container' => \Ht7\CmsSimple\Utility\Container::class,
    ],
    'general' => [
        'mode' => \Ht7\CmsSimple\Kernel\KernelMode::DEBUG,
    ],
    'tasks' => [
        'startup' => [// Locked
            Ht7\CmsSimple\Kernel\Tasks\FixPhpEnvironmentTask::class,
            Ht7\CmsSimple\Kernel\Tasks\AnalyseFolderStructureTask::class,
            Ht7\CmsSimple\Kernel\Tasks\ReadConfigTask::class,
            Ht7\CmsSimple\Kernel\Tasks\CreateCmsContainerTask::class,
            Ht7\CmsSimple\Kernel\Tasks\SetupTaskListTask::class,
        ],
        'warmup' => [// Locked
            Ht7\CmsSimple\Kernel\Tasks\LoadFunctionsTask::class,
            Ht7\CmsSimple\Kernel\Tasks\LoadSingletonsTask::class,
            Ht7\CmsSimple\Kernel\Tasks\StartSessionTask::class,
        ],
        'cms' => [
            Ht7\CmsSimple\Kernel\Tasks\CreateRequestObjectTask::class,
            Ht7\CmsSimple\Kernel\Tasks\Test01Task::class,
            Ht7\CmsSimple\Kernel\Tasks\Test02Task::class,
        ],
        'request' => [],
        'shutdown' => [],
    ]
];
