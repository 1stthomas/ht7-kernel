<?php

namespace Ht7\Kernel\Models;

use \Ht7\Kernel\Models\ArrayIndexable;

/**
 * Basic implementation of the <code>ArrayIndexable</code> interface.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class ArrayIndexedModel implements ArrayIndexable
{

    /**
     * @var     bool                True if the data was modified after the
     *                              initial loading.
     */
    protected $hasToUpdate;

    /**
     * @var     array               The model data as a mutli dimensional assoc
     *                              array.
     */
    protected $items;

    /**
     * Create an instance of the <code>ArrayIndexedModel</code> class.
     *
     * @param   array   $all        The model data. This can be added later.
     */
    public function __construct(array $all = [])
    {
        $this->setAll($all);
        $this->setHasToUpdate(false);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $index)
    {
        return $this->getAll()[$index];
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        return $this->items;
    }

    /**
     * Get wheter the data has been modified after the initital loading or not.
     *
     * @return  bool                    True if the data has been modified after
     *                                  the initial loading.
     */
    public function getHasToUpdate()
    {
        return $this->hasToUpdate;
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $index)
    {
        return array_key_exists($index, $this->getAll());
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $index, $value)
    {
        $this->items[$index] = $value;
        $this->setHasToUpdate(true);
    }

    /**
     * {@inheritdoc}
     */
    public function setAll(array $all)
    {
        $this->items = $all;
    }

    /**
     * Set the state if the data has to be updated or not.
     *
     * @param   bool    $hasToUpdate    True if the data has to be updated.
     */
    public function setHasToUpdate(bool $hasToUpdate)
    {
        $this->hasToUpdate = $hasToUpdate;
    }

}
