<?php

namespace Ht7\Kernel\Storage\Bridges;

/**
 * Base storage query bridge interface.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
interface Bridgeable
{

    /**
     * Get the data model.
     *
     * @return  mixed           The data model.
     */
    public function getDataModel();

    /**
     * Get the model of the present storage.
     *
     * @return  mixed           The model of the present storage.
     */
    public function getStorageModel();

    /**
     * Set the data model.
     *
     * @param   mixed   $model  The data model, where the queried data will be
     *                          accessable.
     * @return  void
     */
    public function setDataModel($model);

    /**
     * Set the storage model.
     *
     * @param   mixed   $model  The model of the present storage.
     * @return  void
     */
    public function setStorageModel($model);
}
