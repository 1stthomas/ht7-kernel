<?php

namespace Ht7\Kernel\Config;

use \InvalidArgumentException;
use \Ht7\Base\Lists\HashList;
use \Ht7\Kernel\Config\LockListType;

/**
 *
 *
 * There are two kinds of LockLists: One for the config definitions and one for
 * the configs.
 *
 * The lock list reflects the loading sequence.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class LockList extends HashList
{

    /**
     * Add a lock list category to the lock list.
     *
     * {@inheritdoc}
     *
     * @param   LockListType        $item   The config path type specific lock
     *                                      list.
     * @return  LockList                    The present lock list.
     * @throws  InvalidArgumentException
     */
    public function add($item)
    {
        if (!($item instanceof LockListType)) {
            $e = 'A LockList item has to be an instance of '
                    . LockListType::class
                    . ', found ' . (is_object($item) ? get_class($item) : gettype($item))
                    . '.';

            throw new InvalidArgumentException($e);
        } elseif ($this->has($item->getHash())) {
            $e = 'The locks for the config path type: ' . $item->getHash()
                    . ' has already been defined.';

            throw new InvalidArgumentException($e);
        }

        $this->cleanup($item);

        return parent::add($item);
    }

    /**
     * Add multiple locks from different config path types.
     *
     * @param   array   $data           Assoc array with the config path types
     *                                  as keys and the locks as an indexed array
     *                                  of config item index.
     * @throws  InvalidArgumentException
     */
    public function addMultiple(array $data)
    {
        if (!empty($data) && array_values($data) === $data) {
            $e = 'The locks array has to be associative.';

            throw new InvalidArgumentException($e);
        }

        foreach ($data as $cpt => $locks) {

            $this->add((new LockListType($cpt, $locks)));
        }
    }

    /**
     * Remove all duplicated locks.
     *
     * A lock for a specific index can be defined only once.
     *
     * @param   LockListType    $lLT        The locklist to clean from duplicates.
     */
    public function cleanup(LockListType $lLT)
    {
        $locksOrg = $this->getLocksSequence();
        $locks = $lLT->getAll();

        $diffs = array_intersect($locksOrg, $locks);

        foreach ($diffs as $dup) {
            $lLT->remove(
                    array_search($dup, $locks)
            );
        }
    }

    /**
     * Get the locks of the present config path type.
     *
     * @param   string      $index              The config path type to get the
     *                                          locks from.
     * @return  \Ht7\Kernel\Config\LockListType
     */
    public function get($index)
    {
        return parent::get($index);
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
        foreach ($this as $configPathType => $locks) {
            if ($locks->has($index)) {
                return $configPathType;
            }
        }

        return false;
    }

    public function getLockListByConfigPathTypes(array $configPathTypes)
    {
        return new LockList($this->getLocksByConfigPathTypes($configPathTypes));
    }

    public function getLocksByConfigPathTypes(array $configPathTypes)
    {
        $locks = [];

        foreach ($configPathTypes as $type) {
            $locks[$type] = $this->get($type);
        }

        return $locks;
    }

    /**
     * Get a one dimensional array of all defined locks.
     *
     * @return  array                       Indexed array of all defined locks.
     */
    public function getLocksSequence()
    {
        return array_reduce(
                $this->getAll(),
                function(array $carry, LockListType $lLT) {
            return array_merge($carry, $lLT->getAll());
        },
                []
        );
    }

    /**
     * Check if a lock for the present index exists.
     *
     * @param   string      $index          The index to check.
     * @return  boolean                     True if the index is locked.
     */
    public function isLocked(string $index)
    {
        return $this->getLockedByConfigPathType($index) === false ? false : true;
    }

    /**
     * Check if the present index is locked by the present config path type.
     *
     * @param   string      $index          The index to check.
     * @param   array       $configPathType The config path type of the locks
     *                                      to check.
     * @return  boolean                     True if the present index is locked
     *                                      by the present config path type.
     */
    public function isLockedByConfigPathType($index, string $configPathType)
    {
        $locks = $this->get($configPathType)->getAll();

        return !empty($locks) && in_array($index, $locks);
    }

    /**
     * Check if the present index is locked by one of the present config path types.
     *
     * @param   string      $index          The index to check.
     * @param   array       $configPathTypes    The config path types of the locks
     *                                      to check.
     * @return  boolean                     True if the present index is locked
     *                                      by the present config path type.
     */
    public function isLockedByConfigPathTypes(string $index, array $configPathTypes)
    {
        return in_array($this->getLockedByConfigPathType($index), $configPathTypes);
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $data)
    {
        $this->addMultiple($data);
    }

}
