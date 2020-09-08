<?php

namespace Ht7\Kernel\Storage;

/**
 * Description of StorageUnit
 *
 * This container composes following elements:
 * - data model to provide the data
 * - storage model to provide the storage access data
 * - in bridge to load data
 * - out bridge to write out data
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class StorageUnit
{

    /**
     * The model where the data of the kernel component is.
     *
     * @var     mixed                   The data model.
     */
    protected $dataModel;

    /**
     * A bridge which can load data from the defined storage.
     *
     * @var     mixed                   The in bridge.
     */
    protected $in;

    /**
     * A bridge which can write data to the defined storage.
     *
     * @var     mixed                   The out bridge.
     */
    protected $out;

    /**
     * The model of the storage, where e.g. the storage type can be requested.
     *
     * @var     mixed                   The in bridge.
     */
    protected $storageModel;

    /**
     * Create an instance of the <code>StorageUnit</code> class.
     *
     * @param   mixed               $dataModel      The model with the data to store
     *                                              or its full qualified class name.
     * @param   StorageModelable    $storaqeModel   The model with the data about
     *                                              the storage.
     * @param   string              $classIn        The full qualified class name
     *                                              of the reader bridge. This
     *                                              property can be added later.
     * @param   string              $classOut       The full qualified class name
     *                                              of the writer bridge. This
     *                                              property can be added later.
     */
    public function __construct($dataModel, $storaqeModel, $classIn = '', $classOut = '')
    {
        if (is_string($dataModel)) {
            $dataModel = new $dataModel();
        }

        $this->setDataModel($dataModel);
        $this->setStorageModel($storaqeModel);

        if (!empty($classIn)) {
            $this->setIn($classIn);
        }
        if (!empty($classOut)) {
            $this->setOut($classOut);
        }
    }

    /**
     * Get the data model.
     *
     * @return  mixed                   The model with the data to read and write.
     */
    public function getDataModel()
    {
        return $this->dataModel;
    }

    /**
     * Get the reader bridge.
     *
     * @return  mixed                   The object which reads the data.
     */
    public function getIn()
    {
        return $this->in;
    }

    /**
     * Get the writer bridge.
     *
     * @return  mixed                   The object which writes the data into the
     *                                  defined storage.
     */
    public function getOut()
    {
        return $this->out;
    }

    /**
     * Get the storage model.
     *
     * @return  StorageModelable        The model with the information about the
     *                                  storage.
     */
    public function getStorageModel()
    {
        return $this->storageModel;
    }

    /**
     * Set the data model.
     *
     * @param   mixed   $dataModel      The model with the data to set.
     * @return  void
     */
    public function setDataModel($dataModel)
    {
        $this->dataModel = $dataModel;
    }

    /**
     * Set the reader bridge.
     *
     * In case the full qualified class name was submitted, this method will
     * create the corresponding instance.
     *
     * @param   mixed   $in             The object (or its full qualified class
     *                                  name) which reads the data.
     * @return  void
     */
    public function setIn($in)
    {
        if (is_string($in)) {
            $in = new $in($this->getDataModel(), $this->getStorageModel());
        }

        $this->in = $in;
    }

    /**
     * Set the writer bridge.
     *
     * In case the full qualified class name was submitted, this method will
     * create the corresponding instance.
     *
     * @param   mixed   $out            The object (or its full qualified class
     *                                  name) which writes the data into the
     *                                  defined storage.
     * @return  void
     */
    public function setOut($out)
    {
        if (is_string($out)) {
            $out = new $out($this->getDataModel(), $this->getStorageModel());
        }

        $this->out = $out;
    }

    /**
     * Set the storage model.
     *
     * @param   mixed   $storageModel   The model with the informations about the
     *                                  storage.
     * @return  void
     */
    public function setStorageModel($storageModel)
    {
        $this->storageModel = $storageModel;
    }

}
