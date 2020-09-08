<?php

namespace Ht7\Kernel\Storage\Files\Models;

use \Ht7\Kernel\Storage\StorageTypes;
use \Ht7\Kernel\Storage\Files\FileExtensions;
use \Ht7\Kernel\Storage\Models\AbstractStorageModel;

/**
 * Basic file model.
 *
 * This model does simply describe the absolute or relative path to the file.
 * Therefor the properties <code>$dir</code>, <code>$extension</code> and
 * <code>$name</code> have to be used.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class FileModel extends AbstractStorageModel
{

    /**
     * Directory of the present file.
     *
     * @var     string              The directory on the server to the file.
     */
    protected $dir;

    /**
     * File extension of the present file.
     *
     * @var     string              The file extension.
     */
    protected $extension;

    /**
     * The name of the present file.
     *
     * @var     string              The file name without its expresion.
     */
    protected $name;

    /**
     * Create an instance of the <code>FileModel</code> class.
     *
     * @param   string  $name       The directory on the server to the file.
     * @param   string  $dir        The file extension.
     * @param   string  $extension  The file name without its expresion.
     */
    public function __construct(string $name, string $dir, string $extension = FileExtensions::PHP)
    {
        $this->storageType = StorageTypes::FILE;

        $this->setDir($dir);
        $this->setExtension($extension);
        $this->setName($name);
    }

    /**
     * Get the directory of the file.
     *
     * @return  string              The directory on the server to the file.
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * Get the file extension.
     *
     * @return  string              The file extension.
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Get the file path which is the combination of the dir, the file name and
     * the file extension.
     *
     * @return  string              The file path.
     */
    public function getFilePath()
    {
        return $this->getDir()
                . DIRECTORY_SEPARATOR
                . $this->getName()
                . (empty($this->getExtension()) ? '' : '.' . $this->getExtension());
    }

    /**
     * Get the file name.
     *
     * @return  string              The file name without its expresion.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the directory of the present file.
     *
     * @param   string  $dir        The directory on the server to the file.
     */
    public function setDir(string $dir)
    {
        $this->dir = $dir;
    }

    /**
     * Set the file extension.
     *
     * @param   string  $extension  The file extension.
     */
    public function setExtension(string $extension)
    {
        $this->extension = $extension;
    }

    /**
     * Set the file name.
     *
     * @param   string  $name       The file name without its expresion.
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

}
