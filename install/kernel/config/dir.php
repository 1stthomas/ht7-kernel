<?php

// The file name of this file is unchangable.
return [
    'root' => getcwd(),
    'dispatcher' => '', // Will be set by the kernel task ReadConfigTask.
    'app' => [
        'root' => '{{root}}' . DIRECTORY_SEPARATOR . 'app',
        'config' => '{{app.root}}' . DIRECTORY_SEPARATOR . 'config',
        'startup' => '{{app.root}}' . DIRECTORY_SEPARATOR . 'bootstrap'
    ],
    'cms' => [
//        'cache' => '{{cms.root}}' . DIRECTORY_SEPARATOR . 'cache',
        'root' => '{{root}}' . DIRECTORY_SEPARATOR . 'cms',
        'config' => '{{cms.root}}' . DIRECTORY_SEPARATOR . 'config',
        'startup' => '{{cms.root}}' . DIRECTORY_SEPARATOR . 'bootstrap'
    ],
    'cache' => '{{cms.root}}' . DIRECTORY_SEPARATOR . 'cache',
    'vendor' => [
        'root' => '{{root}}' . DIRECTORY_SEPARATOR . 'vendor'
    ]
];
