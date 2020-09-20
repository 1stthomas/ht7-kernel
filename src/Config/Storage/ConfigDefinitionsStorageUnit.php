<?php

namespace Ht7\Kernel\Config\Storage;

use \InvalidArgumentException;
use \Ht7\Kernel\Config\Models\ConfigDefinitionsModel;
use \Ht7\Kernel\Config\Storage\ConfigStorageUnit;

/**
 * Description of ConfigStorageUnit
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class ConfigDefinitionsStorageUnit extends ConfigStorageUnit
{

    /**
     * {@inheritdoc}
     *
     * @return  ConfigDefinitionsModel          The data model.
     */
    public function getDataModel()
    {
        return parent::getDataModel();
    }

    /**
     * {@inheritdoc}
     *
     * @param   ConfigDefinitionsModel  $dataModel  The data model.
     * @return  void
     */
    public function setDataModel($dataModel)
    {
        if (!($dataModel instanceof ConfigDefinitionsModel)) {
            $e = 'The dataModel must be an instance of the ' . ConfigDefinitionsModel::class
                    . ' class, found '
                    . (is_object($dataModel) ? get_class($dataModel) : gettype($dataModel));

            throw new InvalidArgumentException($e);
        }

        $this->dataModel = $dataModel;
    }

}
