<?php

namespace Ht7\Kernel\Storage\Files\Bridges;

use \Ht7\Kernel\Config\Models\ConfigFileModel;
use \Ht7\Kernel\Storage\Files\Bridges\AbstractFileBridge;

/**
 * Description of AbstractOut
 *
 * @author Thomas Pluess
 */
abstract class AbstractFileOutBridge extends AbstractFileBridge
{

    /**
     * Flag to decide wheter or not the data should be saved immediately.
     *
     * @var     bool            True if the data should be saved immediately.
     */
    protected $isSaveImmediately;

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
        $this->setIsSaveImmediately($isSaveImmediately);

        parent::__construct($model, $file);
    }

    /**
     * Get the flag if a save should be processed immediately or not.
     *
     * @return   bool                       True if the data should be saved
     *                                      immediately after a change. If false
     *                                      the data has to be saved manually.
     */
    public function getIsSaveImmediately()
    {
        return $this->isSaveImmediately;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function replaceWhere($values, $conditions = null);

    /**
     * {@inheritdoc}
     */
    abstract public function replaceWith($value);

    /**
     *
     * @param   bool    $isImmediately      True if the data should be saved
     *                                      immediately after a change. If false
     *                                      the data has to be saved manually.
     * @return  void
     */
    public function setIsSaveImmediately($isImmediately)
    {
        $this->isSaveImmediately = $isImmediately;
    }

}
