<?php

return [
    'cms/config' => \Ht7\CmsSimple\Config\Config::class,
    'cms/request' => \Ht7\CmsSimple\Http\Request::class,
    'exc/container/psr/notfound' => \Ht7\CmsSimple\Exceptions\EntryNotFoundException::class,
    'exc/container/psr/container' => \Ht7\CmsSimple\Exceptions\ContainerResolvingException::class,
];
