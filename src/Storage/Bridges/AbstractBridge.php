<?php

namespace Ht7\Kernel\Storage\Bridges;

use \Ht7\Kernel\Storage\Bridges\Bridgeable;

/**
 * Base implementation of the <code>Bridgeable</code> interface.
 *
 * It implements the defined, no-complex get-/set-methods.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
abstract class AbstractBridge implements Bridgeable
{

    /**
     * The model with the interessting data.
     *
     * @var     mixed           The queried data.
     */
    protected $dataModel;

    /**
     * The model with the informations about the storage.
     *
     * @var     mixed           The informations about the storage.
     */
    protected $storageModel;

    /**
     * Create an instance of an extender of the <code>AbstractBridge</code> class.
     *
     * @param   mixed   $dataModel          The data model, where the data can
     *                                      be accessed.
     * @param   mixed   $storageModel       The model with the informations
     *                                      about the storage.
     */
    public function __construct($dataModel, $storageModel)
    {
        $this->setDataModel($dataModel);
        $this->setStorageModel($storageModel);
    }

    /**
     * {@inheritdoc}
     */
    public function getDataModel()
    {
        return $this->dataModel;
    }

    /**
     * {@inheritdoc}
     */
    public function getStorageModel()
    {
        return $this->storageModel;
    }

    /**
     * {@inheritdoc}
     */
    public function setDataModel($dataModel)
    {
        $this->dataModel = $dataModel;
    }

    /**
     * {@inheritdoc}
     */
    public function setStorageModel($storageModel)
    {
        $this->storageModel = $storageModel;
    }

}
