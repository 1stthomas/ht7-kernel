<?php

namespace Ht7\Kernel\Config\Storage;

use \InvalidArgumentException;
use \Ht7\Base\Lists\HashList;
use \Ht7\Kernel\Config\ConfigLoadingSequence;
use \Ht7\Kernel\Config\Storage\ConfigStorageUnit;
use \Ht7\Kernel\Config\Storage\DummyStorageUnit;

/**
 * The <code>StorageUnitList</code> class contains the <code>StorageUnit</code>
 * instances of the present config category. The sequence is reverse to the
 * loading sequence of the configs.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class StorageUnitList extends HashList
{

    public function __construct(ConfigLoadingSequence $sequence)
    {
        $susSequence = array_reverse($sequence->getSequence(), true);

        $susDummy = array_map(
                function(string $cpt) {
            return new DummyStorageUnit($cpt);
        },
                $susSequence
        );

        parent::__construct($susDummy);
    }

    /**
     * Add a storage unit to the storage unit list. The su will only be added,
     * if an instance of <code>DummyStorageUnit</code> or <code>ConfigStorageUnit</code>
     * is present.
     *
     * @param   DummyStorageUnit|ConfigStorageUnit  $item
     * @return  void
     * @throws InvalidArgumentException
     */
    public function add($item)
    {
        if ($item instanceof DummyStorageUnit) {
            parent::add($item);
        } elseif ($item instanceof ConfigStorageUnit) {
            if ($this->has($item->getHash())) {
                $this->items[$item->getHash()] = $item;
            } else {
                $e = 'The config path type of the item is undefined: ' . $item->getHash()
                        . '. You may need to fix the loading sequence of the configs.';

                throw new InvalidArgumentException($e);
            }
        } else {
            $e = 'The item must be an instance of the ConfigStorageUnit'
                    . ' class, found '
                    . (is_object($item) ? get_class($item) : gettype($item));

            throw new InvalidArgumentException($e);
        }
    }

    /**
     * Get the storage unit of the present config path type.
     *
     * @param   string      $index      The config path type of the storage unit
     *                                  to get.
     * @return  ConfigStorageUnit       The config specific storage unit.
     */
    public function get($index)
    {
        return parent::get($index);
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
        if ($this->has($configPathType)) {
            if ($this->get($configPathType)->getDataModel()->has($index)) {
                return $this->get($configPathType)->getDataModel()->get($index);
            }
        }

        return $default;
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
        foreach ($this as $su) {
            if ($su instanceof DummyStorageUnit) {
                continue;
            }
            if (in_array($su->getStorageModel()->getConfigPathType(), $configPathTypes) && $su->getDataModel()->has($index)) {
                return $su->getDataModel()->get($index);
            }
        }

        return $default;
    }

    public function getByConfigPathTypesExcluded(array $configPathTypes)
    {
        return array_filter(
                $this->getAll(),
                function($su) use ($configPathTypes) {
            return $su instanceof ConfigStorageUnit && !in_array($su->getStorageModel()->getConfigPathType(), $configPathTypes);
        }
        );
    }

    /**
     * Get the item with the present index, but respect only the present config
     * path type or those config files, which are loaded before the present one.
     *
     * @param   string      $index              The index of the item to get.
     * @param   string|null $configPathType     The config path type as loading sequence
     *                                          limit.
     *                                          <code>\Ht7\Kernel\Config\ConfigPathTypes</code>
     * @param   mixed       $default            The value, which should be returned,
     *                                          if there is no item with the present
     *                                          index.
     * @return  mixed                           The found item or the defined default
     *                                          value.
     */
    public function getByConfigPathTypeMax(string $index, string $configPathType = null, $default = null)
    {
        $isFound = $configPathType === null ? true : false;

        foreach ($this as $cpt => $su) {
            if (!$isFound && $cpt === $configPathType) {
                $isFound = true;
            }

            if (!$isFound) {
                continue;
            }

            if ($su instanceof ConfigStorageUnit && $su->getDataModel()->has($index)) {
                return $su->getDataModel()->get($index);
            }
        }

        return $default;
    }

    /**
     * Get the storage unit of the previous config path type relative to the
     * present one in the reversed loading sequence.
     *
     * @param   string      $cpt        The config path type of the storage unit
     *                                  to get.
     * @return  ConfigStorageUnit       The config specific storage unit.
     */
    public function getNext(string $cpt)
    {
        $keys = array_keys($this->items);
        $cIndex = array_search($cpt, $keys);

        if ($cIndex === false) {
            $e = 'Invalid config path type: ' . $cpt;

            throw new InvalidArgumentException($e);
        } else {
            if ($cIndex === 0) {
                return null;
            } else {
                return $this->get($keys[$cIndex - 1]);
            }
        }
    }

    /**
     * Get the storage unit of the next config path type relative to the
     * present one in the reversed loading sequence.
     *
     * @param   string      $cpt        The config path type of the storage unit
     *                                  to get.
     * @return  ConfigStorageUnit       The config specific storage unit.
     */
    public function getPrevious(string $cpt)
    {
        $keys = array_keys($this->items);
        $cIndex = array_search($cpt, $keys);

        if ($cIndex === false) {
            $e = 'Invalid config path type: ' . $cpt;

            throw new InvalidArgumentException($e);
        } else {
            if (count($keys) === ($cIndex + 1)) {
                return null;
            } else {
                return $this->get($keys[$cIndex + 1]);
            }
        }
    }

}
