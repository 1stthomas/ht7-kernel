<?php

namespace Ht7\Kernel\Storage\Files\Bridges;

use \Ht7\Kernel\Storage\Files\Models\FileModel;
use \Ht7\Kernel\Storage\Bridges\AbstractBridge;

/**
 * Basic implementation of a file reader/writer bridge.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
abstract class AbstractFileBridge extends AbstractBridge
{

    /**
     * {@inheritdoc}
     *
     * @param   FileModel   $storageModel   The model with the informations about
     *                                      the storage.
     */
    public function __construct($dataModel, FileModel $storageModel)
    {
        parent::__construct($dataModel, $storageModel);
    }

}
