<?php

namespace Ht7\Kernel\Storage\Files\Bridges;

use \Ht7\Kernel\Storage\Files\FileExtensions;
use \Ht7\Kernel\Storage\Files\Bridges\AbstractFileBridge;
use \Ht7\Kernel\Storage\Bridges\BridgeInable;

/**
 * Abstract implementation of a file reader bridge.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
abstract class AbstractFileInBridge extends AbstractFileBridge implements BridgeInable
{

    public function getBy()
    {

    }

    public function getById()
    {

    }

    public function getByWhere()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $storageModel = $this->getStorageModel();

        $filePath = $storageModel->getDir()
                . DIRECTORY_SEPARATOR
                . $storageModel->getName()
                . '.'
                . $storageModel->getExtension();

        /* @var $model \Ht7\Kernel\Config\Models\AbstractConfig */
        $dataModel = $this->getDataModel();

        if (file_exists($filePath)) {
            $content = require $filePath;

            $dataModel->setAll($this->sanitize($content));
        } else {
            $dataModel->setAll([]);
        }
    }

    /**
     * Sanitize a string.
     *
     * - .json content will be decoded
     * - all other file extension will return the input value.
     *
     * @param   mixed   $content        The content to sanitize.
     * @return  string                  The sanitzed content.
     */
    public function sanitize($content)
    {
        switch ($this->getStorageModel()->getExtension()) {
            case FileExtensions::PHP:
                return $content;
            case FileExtensions::JSON:
                return json_decode($content, true);
            default:
                return $content;
        }
    }

}
