<?php

namespace Ht7\Kernel\Config\Storage;

use \InvalidArgumentException;
use \Ht7\Kernel\Config\Models\ConfigFileModel;
use \Ht7\Kernel\Models\ArrayDotIndexedModel;
use \Ht7\Kernel\Storage\Files\Bridges\AbstractFileInBridge;

/**
 * Generic config reader class.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class GenericConfigIn extends AbstractFileInBridge
{

    /**
     * {@inheritdoc}
     *
     * @param   ArrayDotIndexedModel    $dataModel      The data model.
     * @param   ConfigFileModel         $storageModel   The storage informations.
     */
    public function __construct($dataModel, ConfigFileModel $storageModel)
    {
        parent::__construct($dataModel, $storageModel);
    }

    /**
     * {@inheritdoc}
     *
     * @param   ArrayDotIndexedModel    $model  The model with the data.
     */
    public function setDataModel($model)
    {
        if (!($model instanceof ArrayDotIndexedModel)) {
            $e = 'The model has to be an instance of ' . ArrayDotIndexedModel::class
                    . ' found ' . (is_object($model) ? get_class($model) : gettype($model)) . '.';

            throw new InvalidArgumentException($e);
        }

        parent::setDataModel($model);
    }

}
