<?php

namespace Ht7\Kernel\Config\Storage;

use \InvalidArgumentException;
use \Ht7\Kernel\Config\Models\ConfigFileModel;
use \Ht7\Kernel\Config\Models\GenericDotIndexedConfigModel;
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
     * @param   ConfigFileModel     $storageModel   The storage informations.
     */
    public function __construct($dataModel, ConfigFileModel $storageModel)
    {
        parent::__construct($dataModel, $storageModel);
    }

    /**
     * {@inheritdoc}
     *
     * @param   GenericDotIndexedConfigModel    $model  The model with the data.
     */
    public function setDataModel($model)
    {
        if (!($model instanceof GenericDotIndexedConfigModel)) {
            $e = 'The model has to be an instance of ' . get_class(GenericDotIndexedConfigModel::class)
                    . ' found ' . (is_object($model) ? get_class($model) : gettype($model)) . '.';

            throw new InvalidArgumentException($e);
        }

        parent::setDataModel($model);
    }

}
