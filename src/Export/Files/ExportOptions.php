<?php

namespace Ht7\Kernel\Export\Files;

use \Ht7\Kernel\Models\ArrayDotIndexedModel;

/**
 * Description of ExportOptions
 *
 * @author Thomas Pluess
 */
class ExportOptions extends ArrayDotIndexedModel
{

    protected $items = [
        'extensions' => [
            \Ht7\Kernel\Storage\Files\FileExtensions::PHP => [
                'has_extra_line_on_start' => false,
                'indention' => 4,
                'quotation_mark' => "'",
            ],
            \Ht7\Kernel\Storage\Files\FileExtensions::JSON => [
                'flags' => 0,
            ],
        ],
        'sanitize' => [
            'keep_class_definitions' => true,
//            'quotation_mark' => [
//                'default' => '\'',
//                'types' => 0
//            ]
        ]
    ];

    public function setAll(array $all)
    {
        if (!empty($all)) {
            parent::setAll($all);
        }
    }

}
