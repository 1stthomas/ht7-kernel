<?php

namespace Ht7\Kernel\Config\Models;

use \Ht7\Kernel\Storage\Files\Models\FileModel;
use \Ht7\Kernel\Storage\Files\FileExtensions;

/**
 * File model for the config data model.
 *
 * This model describes informations about the storage.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class ConfigFileModel extends FileModel
{

    /**
     * The config path type of the present model.
     *
     * @var     string      One of the constants defined in the
     *                      <code>\Ht7\Kernel\Config\ConfigPathType</code>
     *                      enum.
     */
    protected $configPathType;

    /**
     * Get an instance of the <code>ConfigFileModel</code> class.
     *
     * @param   string  $name           The file name.
     * @param   string  $dir            The directory to the file on the server.
     * @param   string  $configPathType The config path type of the present model.
     *                                  Use one of the constants defined in the
     *                                  <code>\Ht7\Kernel\Config\ConfigPathType</code>
     *                                  enum.
     */
    public function __construct(string $name, string $dir, string $configPathType)
    {
        $this->setConfigPathType($configPathType);

        parent::__construct($name, $dir, FileExtensions::PHP);
    }

    /**
     * Get the config path type of the present storage unit.
     *
     * @return  int                     The config path type of the present model.
     *                                  The value is one of the constants defined
     *                                  in the
     *                                  <code>\Ht7\Kernel\Config\ConfigPathType</code>
     *                                  enum.
     */
    public function getConfigPathType()
    {
        return $this->configPathType;
    }

    /**
     * Set the config path type of the present storage unit.
     *
     * @param   string  $configPathType The config path type of the present model.
     *                                  Use one of the constants defined in the
     *                                  <code>\Ht7\Kernel\Config\ConfigPathType</code>
     *                                  enum.
     */
    public function setConfigPathType(string $configPathType)
    {
        $this->configPathType = $configPathType;
    }

}
