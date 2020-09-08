<?php

namespace Ht7\Kernel\Config\Categories;

use \RuntimeException;
use \Ht7\Kernel\Config\ConfigPathTypes;
use \Ht7\Kernel\Config\ConfigLoadingSequence;
use \Ht7\Kernel\Storage\StorageUnit;

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
abstract class AbstractIndexedConfigCategory
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
     * @var     array               Assoc array of locks separated by their config
     *                              types. The index are strings of the related
     *                              config path types.
     */
    protected $locks;

    /**
     * This array holds the main components of the present container.
     *
     * @var     array               Indexed array of storage units.
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

    /**
     * Add a storage unit to the container.
     *
     * @param   StorageUnit     $su         The storage unit to add.
     * @return  void
     * @throws RuntimeException
     */
    public function add(StorageUnit $su)
    {
        if ($this->isLoadedImmediately()) {
            $su->getIn()->load();
        }

        $cpt = $su->getStorageModel()->getConfigPathType();

        if (array_key_exists($cpt, $this->sus)) {
            if (is_object($this->sus[$cpt])) {
                $e = 'A confg path type: ' . $cpt . ' has already been defined.';

                throw new RuntimeException($e);
            } else {
                $this->sus[$cpt] = $su;
            }
        } else {
            $e = 'A config path type: ' . $cpt . ' has not been defined.';

            throw new RuntimeException($e);
        }
    }

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
        $lockedByConfigPathType = $this->getLockedByConfigPathType($index);

        if ($lockedByConfigPathType) {
//            return $this->getByLevel();
            return $this->getByConfigPathType($index, $lockedByConfigPathType, $default);
        }

        /* @var $su StorageUnit */
        foreach ($this->sus as $su) {
            if (is_object($su) && $su->getDataModel()->has($index)) {
                return $su->getDataModel()->get($index);
            }
        }

        return $default;
    }

    /**
     * Get the item with the specified index of a specific config path type.
     *
     * This method calls internally <code>$this->getByConfigPathTypes()</code>.
     *
     * @param   string  $index              The index of the item to get.
     * @param   string  $configPathType     The config path type which describes
     *                                      the storage location of the config
     *                                      file. Use one of the constants defined
     *                                      in <code>\Ht7\Kernel\Config\ConfigPathTypes</code>
     * @param   mixed   $default            The value, which should be returned,
     *                                      if there is no item with the present
     *                                      index.
     * @return  mixed                       The found item or the defined default
     *                                      value.
     */
    public function getByConfigPathType(string $index, string $configPathType, $default = null)
    {
        return $this->getByConfigPathTypes($index, [$configPathType], $default);
    }

    /**
     * Get the item with the specified index of specific config path types.
     *
     * This method calls internally <code>$this->getByConfigPathTypes()</code>.
     *
     * @param   string  $index              The index of the item to get.
     * @param   array   $configPathTypes    The config path type which describes
     *                                      the storage location of the config
     *                                      file. Use the constants defined in
     *                                      <code>\Ht7\Kernel\Config\ConfigPathTypes</code>
     * @param   mixed   $default            The value, which should be returned,
     *                                      if there is no item with the present
     *                                      index.
     * @return  mixed                       The found item or the defined default
     *                                      value.
     */
    public function getByConfigPathTypes(string $index, array $configPathTypes, $default = null)
    {
        /* @var $su StorageUnit */
        foreach ($this->sus as $su) {
            if (!is_object($su)) {
                continue;
            }
            if (in_array($su->getStorageModel()->getConfigPathType(), $configPathTypes) && $su->getDataModel()->has($index)) {
                return $su->getDataModel()->get($index);
            }
        }

        return $default;
    }

    /**
     * Get the item with the present index, but respect only the present config
     * path type or those config files, which are loaded before the present one.
     *
     * @param   int     $configPathType     The config path type as loading sequence
     *                                      limit.
     */
    public function getByLevel(int $configPathType)
    {

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
     * Get the config path type where the present index is locked.
     *
     * @param   string      $index          The index to check.
     * @return  string|boolean              The the first config path type where
     *                                      the index is locked.
     */
    public function getLockedByConfigPathType(string $index)
    {
        foreach ($this->getLocks() as $configPathType => $locks) {
            if (in_array($index, $locks)) {
                return $configPathType;
            }
        }

        return false;
    }

    /**
     * Get all defined locks of the present config category.
     *
     * @return  array                   Assoc array of locks separated by their config
     *                                  types. The index are integers of the related
     *                                  config path types.
     */
    public function getLocks()
    {
        return $this->locks;
    }

    public function getLocksByConfigPathType(string $configPathType)
    {
        return isset($this->locks[$configPathType]) ? $this->locks[$configPathType] : [];
    }

    public function getLocksByConfigPathTypes(array $configPathTypes)
    {
        $locks = [];

        foreach ($configPathTypes as $type) {
            $locks[$type] = $this->getLocksByConfigPathType($type);
        }

        return $locks;
    }

    /**
     * Get the storage units of this config category.
     *
     * @return  array                   The storage units of this config category
     *                                  as an assoc array with the related config
     *                                  path types as index.
     */
    public function getSUs()
    {
        return $this->sus;
    }

    public function getSUByConfigPathType(string $configPathType)
    {
        foreach ($this->sus as $su) {
            if (is_object($su) && $su->getStorageModel()->getConfigPathType() === $configPathType) {
                return $su;
            }
        }
    }

    public function getSUsByConfigPathTypes(array $configPathTypes = [])
    {
        $susFiltered = [];

        foreach ($this->sus as $su) {
            if (is_object($su) && in_array($su->getStorageModel()->getConfigPathType(), $configPathTypes)) {
                $susFiltered[$su->getStorageModel()->getConfigPathType()] = $su;
            }
        }

        return $susFiltered;
    }

    public function getSUsByConfigPathTypesExcluded(array $configPathTypes = [])
    {
        $susFiltered = [];

        foreach ($this->sus as $key => $su) {
            if (is_object($su) && !in_array($su->getStorageModel()->getConfigPathType(), $configPathTypes)) {
                $susFiltered[$key] = $su;
            }
        }

        return $susFiltered;
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

    abstract public function initStorageUnits(array $sus);

    public function isLoadedImmediately()
    {
        return $this->isLoadedImmediately;
    }

    public function isLocked($index)
    {
        $this->getLockedByConfigPathType($index) === false ? false : true;
    }

    public function isLockedByConfigPathType($index, string $configPathType)
    {
        $locks = $this->getLocksByConfigPathType($configPathType);

        return !empty($locks) && in_array($index, $locks);
    }

    public function isLockedByConfigPathTypes($index, array $configPathTypes)
    {
        return in_array($this->getLockedByConfigPathType($index), $configPathTypes);

//        $locks = $this->getLocksByConfigPathTypes($configPathTypes);
//
//        foreach ($configPathTypes as $locks) {
//            if (in_array($index, $locks)) {
//                return true;
//            }
//        }
//
//        return false;
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
     * Set all locks of the present config category container.
     *
     * @param   array   $locks          Assoc array of locks separated by their
     *                                  config types. The index are integers of
     *                                  the related config path types.
     * @return  void
     */
    public function setLocks(array $locks)
    {
        $this->locks = $locks;
    }

    public function setLocksByConfigPathType(array $locks, int $configPathType)
    {
        // @todo: Hier muss noch zu den Kernel Locks gedifft werden!!
        $this->locks[$configPathType] = $locks;
    }

    public function createModel(array $values = [])
    {
        // Get the  config model of the kernel config path type.
        $class = get_class($this->getSUByConfigPathType(ConfigPathTypes::KERNEL)->getDataModel());

        return new $class($values);
    }

    protected function initGetKernelSU(array $sus)
    {
        for ($i = 0; $i < count($sus); $i++) {
            if ($sus[$i]->getStorageModel()->getConfigPathType() === ConfigPathTypes::KERNEL) {
                return $sus[$i];
            }
        }

        $e = 'Missing storage unit of the kernel.';

        throw new RuntimeException($e);
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
//        if (!empty($this->locksKernel) || !empty($this->locksApp)) {
            return;
        }

        /* @var $model \Ht7\Kernel\Models\ArrayIndexedModel */
        $model = $this->createModel($data);
        $cpts = [];

        foreach ($locksAll as $cpt => $locks) {
            $cpts[] = $cpt;

            foreach ($locks as $lock) {
                $model->set($lock, $this->getByConfigPathTypes($lock, $cpts));
            }
        }

        return $model->getAll();
    }

}
