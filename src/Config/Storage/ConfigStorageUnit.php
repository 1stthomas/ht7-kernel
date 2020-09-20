<?php

namespace Ht7\Kernel\Config\Storage;

use \InvalidArgumentException;
use \Ht7\Base\Lists\Hashable;
use \Ht7\Kernel\Config\Models\ConfigFileModel;
use \Ht7\Kernel\Config\Models\GenericConfigModel;
use \Ht7\Kernel\Storage\StorageUnit;

/**
 * Description of ConfigStorageUnit
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class ConfigStorageUnit extends StorageUnit implements Hashable
{

    /**
     * Get the config path type of the present storage model.
     *
     * @return  string                          The config path type of the used
     *                                          storage model.
     */
    public function getHash()
    {
        return $this->getStorageModel()->getConfigPathType();
    }

    /**
     * {@inheritdoc}
     *
     * @return  GenericConfigModel              The data model.
     */
    public function getDataModel()
    {
        return $this->dataModel;
    }

    /**
     * {@inheritdoc}
     *
     * @return  ConfigFileModel                 The storage model.
     */
    public function getStorageModel()
    {
        return $this->storageModel;
    }

    /**
     * {@inheritdoc}
     *
     * @param   GenericConfigModel  $dataModel      The data model.
     * @return  void
     */
    public function setDataModel($dataModel)
    {
        if ($dataModel instanceof GenericConfigModel) {
            parent::setDataModel($dataModel);
        } else {
            $e = 'The dataModel must be an instance of the ' . GenericConfigModel::class
                    . ' class, found '
                    . (is_object($dataModel) ? get_class($dataModel) : gettype($dataModel));

            throw new InvalidArgumentException($e);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param   ConfigFileModel     $storageModel   The storage model.
     * @return  void
     */
    public function setStorageModel($storageModel)
    {
        if ($storageModel instanceof ConfigFileModel) {
            parent::setStorageModel($storageModel);
        } else {
            $e = 'The storageModel must be an instance of the ' . ConfigFileModel::class
                    . ' class, found '
                    . (is_object($storageModel) ? get_class($storageModel) : gettype($storageModel));

            throw new InvalidArgumentException($e);
        }
    }

}
