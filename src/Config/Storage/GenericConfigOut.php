<?php

namespace Ht7\Kernel\Config\Storage;

use \Ht7\Kernel\Config\Models\ConfigFileModel;
use \Ht7\Kernel\Config\Models\ConfigDefinitionsModel;
use \Ht7\Kernel\Export\Files\JsonExport;
use \Ht7\Kernel\Models\ArrayDotIndexedModel;
use \Ht7\Kernel\Storage\Files\Bridges\AbstractFileOutBridge;
use \Ht7\Kernel\Storage\Files\FileExtensions;

/**
 * Description of AbstractConfigStorageBridgeIn
 *
 * @author Thomas Pluess
 */
class GenericConfigOut extends AbstractFileOutBridge
{

    /**
     * Create an instance of the <code>GenericConfigIn</code> class.
     *
     * @param   mixed   $dataModel          The data model, where the data can
     *                                      be accessed.
     * @param   mixed   $storageModel       The model with the informations
     *                                      about the storage.
     */
    public function __construct($model, ConfigFileModel $file, $isSaveImmediately = true)
    {
        parent::__construct($model, $file, $isSaveImmediately);
    }

    // @todo: braucht noch ein value!!
    public function addAfterBy($condition)
    {
        ;
    }

    public function addBeforeBy($condition)
    {
        ;
    }

    public function append()
    {
        ;
    }

    public function delete($index)
    {
        $parts = explode('.', $index);

        if (count($parts) > 1) {
            // The searched array is an inner array.
            $lastPart = array_pop($parts);

            // Retrieve the array, where the searched element is an element of.
            $all = $this->getDataModel()->get(implode('.', $parts));
            unset($all[$lastPart]);

            // Readd the cleaned array.
            $this->getDataModel()->set(implode('.', $parts), $all);
        } elseif (count($parts) === 1) {
            // The searched element is an item from the root array.
            $all = $this->getDataModel()->getAll();
            unset($all[$index]);

            // Reset the cleaned values.
            $this->getDataModel()->setAll($all);
        } else {
            return;
        }

        if ($this->getIsSaveImmediately()) {
            $this->write();
        } else {
            $this->getDataModel()->setHasToUpdate(true);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return  ArrayDotIndexedModel        The data model.
     */
    public function getDataModel()
    {
        return $this->dataModel;
    }

    public function replaceByIndex(string $index, $value)
    {
        $this->getDataModel()->set($index, $value);

        if ($this->getIsSaveImmediately()) {
            $this->write();
        }
    }

    public function replaceWhere($values, $conditions = null)
    {
        ;
    }

    public function replaceWith($param)
    {

    }

    public function write()
    {
        /* @var $storage ConfigFileModel */
        $storage = $this->getStorageModel();

        switch ($storage->getExtension()) {
            case FileExtensions::JSON:

                break;
            case FileExtensions::PHP:
            default:
                $configConfig = $this->getDataModel()->getDefinitions();

                if ($configConfig instanceof ConfigDefinitionsModel) {
                    $configConfig = $configConfig->getDefinitions();
                }

                $class = $configConfig->get('storage.export.extensions.' . FileExtensions::PHP . '.classes.factory');

                $writer = (new $class())->createByConfigDefintionsConfig(
                        $configConfig,
                        $storage->getFilePath(),
                        $this->getDataModel()->getAll()
                );
                $writer->export();

                break;
        }

        $this->getDataModel()->setHasToUpdate(false);
    }

}
