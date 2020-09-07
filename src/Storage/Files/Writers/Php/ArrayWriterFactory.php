<?php

namespace Ht7\Kernel\Storage\Files\Writers\Php;

use \Ht7\Kernel\Models\ArrayDotIndexedModel;
use \Ht7\Kernel\Storage\Files\FileExtensions;

//use \Ht7\Kernel\Storage\Files\Writers\Php\Transformers\;

/**
 * Description of WriterOptionsFactory
 *
 * @author Thomas Pluess
 */
class ArrayWriterFactory
{

//    public function __construct()
//    {
//        ;
//    }

    public function createByConfigDefintionsConfig(
            ArrayDotIndexedModel $config,
            string $target,
            array $data
    )
    {
        $ns = 'export.extensions.' . FileExtensions::PHP;

        $classOptions = $config->get($ns . '.classes.options');
        $classSanitizerList = $config->get($ns . '.classes.sanitizer_list');
        $classTransformer = $config->get($ns . '.classes.transformer');
        $classWriter = $config->get($ns . '.classes.writer');

        $options = new $classOptions(
                $config->get($ns . '.general.indention'),
                array_reduce(
                        $config->get($ns . '.general.flags'),
                        function($carry, $item) {
                    return $carry | $item;
                })
        );

        $sanitizers = [];

        foreach ($config->get($ns . '.sanitizers') as $sanitizerConfig) {
            foreach ($sanitizerConfig['classes'] as $class) {
                $sanitizers[] = [
                    (new $class($sanitizerConfig['options'])),
                    $sanitizerConfig['flags']
                ];
            }
        }

        $sL = new $classSanitizerList($sanitizers);
        $transformer = new $classTransformer($options, $sL);

        return (new $classWriter($target, $transformer, $data));
    }

}
