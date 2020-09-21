<?php

namespace Ht7\Kernel\Config\Categories;

use \RuntimeException;
use \Ht7\Base\Lists\Hashable;
use \Ht7\Kernel\Config\ConfigPathTypes;
use \Ht7\Kernel\Config\ConfigLoadingSequence;
use \Ht7\Kernel\Config\LockList;
use \Ht7\Kernel\Storage\StorageUnit;
use \Ht7\Kernel\Config\Storage\ConfigStorageUnit;
use \Ht7\Kernel\Config\Storage\StorageUnitList;

/**
 * This is the base config category class. It is a container to serve the storage
 * units defined for the present category.
 *
 * This class has 1 abstract method:
 * - <code>initStorageUnits(array $sus)</code>
 *
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
abstract class AbstractIndexedConfigCategory implements Hashable
{

    /**
     * The category which is, if not otherwise defined, the file name of the
     * underlying data.
     *
     * @var     string              The category of the present config container.
     */
    protected $id;

    /**
     * Flag to define wheter a data model of an adding storage unit has to be
     * loaded in this move.
     *
     * @var     bool                True if the data models will be loaded immediatelly
     *                              after adding to the present container.
     */
    protected $isLoadedImmediately;

    /**
     * Container with the config path types in order of there loading.
     *
     * @var     ConfigLoadingSequence   The loading sequence of the config files.
     */
    protected $loadingSequence;

    /**
     * All defined locks of this config category. A lock will block later loading
     * configs to override the value.
     *
     * @var     LockList            A list of <code>LockListType</code>s.
     */
    protected $locks;

    /**
     * The storage unit list.
     *
     * @var     StorageUnitList     A hash list with config storage unit items.
     */
    protected $sus;

    /**
     * Create an instance of a class which extends this abstract class.
     *
     * @param   string  $id                     The category of the present config.
     * @param   array   $storageUnits           Storage units to add initially.
     * @param   array   $locks                  Locks to add. This is an assoc
     *                                          array with the config path types
     *                                          as string as index and the config
     *                                          path type related locks.
     * @param   bool    $isLoadedImmediately    True if the data models should
     *                                          be loaded by adding a storage unit.
     */
    public function __construct(string $id, array $storageUnits = [], array $locks = [], bool $isLoadedImmediately = true)
    {
        $this->setId($id);
        $this->setIsLoadedImmediately($isLoadedImmediately);
        $this->setLocks($locks);

        if (!empty($storageUnits)) {
            $this->initStorageUnits($storageUnits);
        }
    }

    abstract public function initStorageUnits(array $sus);

    /**
     * @todo ..!
     */
    public function cache()
    {
        $sus = [];
        $cachables = [
            ConfigPathTypes::KERNEL,
            ConfigPathTypes::APP,
            ConfigPathTypes::OVERRIDE,
        ];

        foreach ($this->sus as $su) {
            if (in_array($su->getFile()->getConfigPathType(), $cachables)) {
                $sus[] = $su;
            }
        }

        return $this->mergeSUs($sus);
    }

    /**
     * Get the item with the present index.
     *
     * @param   string  $index      The index of the item to get.
     * @param   mixed   $default    The value, which should be returned, if there
     *                              is no item with the present index.
     * @return  mixed               The found item or the defined default value.
     */
    public function get(string $index, $default = null)
    {
        $configPathTypeMax = $this->getLocks()->getLockedByConfigPathType($index);

        if ($configPathTypeMax) {
            return $this->getStorageUnitList()->getByConfigPathTypeMax($index, $configPathTypeMax, $default);
        }
//
        return $this->getStorageUnitList()->getByConfigPathTypeMax($index);
    }

    /**
     * {@inheritdoc}
     */
    public function getHash()
    {
        return $this->getId();
    }

    /**
     * Get the id of the present config container.
     *
     * This is an alias for the config category.
     *
     * @return  string                      Get the category of the present config.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the loading sequence container.
     *
     * @return  ConfigLoadingSequence       The loading sequence container.
     */
    public function getLoadingSequence()
    {
        return $this->loadingSequence;
    }

    /**
     * Get all defined locks of the present config category.
     *
     * @return  LockList                A list of <code>LockListType</code> classes.
     */
    public function getLocks()
    {
        return $this->locks;
    }

    /**
     * Get the storage unit list.
     *
     * @return  StorageUnitList
     */
    public function getStorageUnitList()
    {
        return $this->sus;
    }

    /**
     * Check wheter an item with the present index is defined or not.
     *
     * @param   string      $index      The index to check.
     * @return  bool                    True if an item with the present index
     *                                  could be found.
     */
    public function has(string $index)
    {
        $default = '@@no_index_found@@';

        return $this->get($index, $default) === $default;
//        foreach ($this->sus as $su) {
//            if ($su->getDataModel()->has($index)) {
//                return true;
//            }
//        }
//
//        return false;
    }

    public function isLoadedImmediately()
    {
        return $this->isLoadedImmediately;
    }

    public function rGet(string $index, $default = null)
    {
        $this->sus = array_reverse($this->sus);
        $item = $this->get($index, $default);
        $this->sus = array_reverse($this->sus);

        return $item;
    }

//    public function set(string $index, $value, int $configPathType = ConfigPathTypes::CACHE)
//    {
//        foreach ($this->configs as $config) {
//            if ($config->getConfigPathType() === $configPathType) {
//                $config->set($index, $value);
//            }
//        }
//    }

    public function setId(string $id)
    {
        $this->id = $id;
    }

    public function setIsLoadedImmediately($isLoadedImmediately)
    {
        $this->isLoadedImmediately = $isLoadedImmediately ? true : false;
    }

    /**
     * Set the loading sequence of the present config category.
     *
     * @param   ConfigLoadingSequence   $sequence   The loading sequence container.
     * @return  void
     */
    public function setLoadingSequence(ConfigLoadingSequence $sequence)
    {
        $this->loadingSequence = $sequence;
    }

    /**
     * Set all locks of the present config category container. Using this method
     * will replace an existing one.
     *
     * @param   array   $locks          Assoc array of locks separated by their
     *                                  config types. The index are integers of
     *                                  the related config path types.
     * @return  void
     */
    public function setLocks(array $locks)
    {
        $this->locks = new LockList($locks);
    }

    public function createModel(array $values = [])
    {
        // Get the  config model of the kernel config path type.
        $class = get_class(
                $this->getStorageUnitList()
                        ->get(ConfigPathTypes::KERNEL)
                        ->getDataModel()
        );

        return new $class($values);
    }

    /**
     * Get the data array of all merged data models.
     *
     * @param type $sus
     * @return type
     */
    protected function mergeSUs($sus)
    {
        $data = [];

        foreach ($sus as $su) {
            $data = array_merge($su->getDataModel()->getAll(), $data);
        }

        return $this->replaceLockedValues($data);
    }

    protected function replaceLockedValues(array $data)
    {
        $locksAll = $this->getLocks();

        if (empty($locksAll)) {
            return;
        }

        /* @var $model \Ht7\Kernel\Models\ArrayIndexedModel */
        $model = $this->createModel($data);
        $cpts = [];

        foreach ($locksAll as $cpt => $locks) {
            $cpts[] = $cpt;

            foreach ($locks as $lock) {
                $model->set($lock, $this->getStorageUnitList()->get($lock, $cpts));
            }
        }

        return $model->getAll();
    }

}
