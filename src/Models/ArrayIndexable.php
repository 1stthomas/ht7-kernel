<?php

namespace Ht7\Kernel\Models;

/**
 * Implementations of this interface can get a model property by its index.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
interface ArrayIndexable
{

    /**
     * Get the element with the present index.
     *
     * @param   string  $index      The index of the element to retrieve.
     * @return  mixed               The element with the present index.
     */
    public function get(string $index);

    /**
     * Get all elements.
     *
     * @return  mixed               All elements of the model.
     */
    public function getAll();

    /**
     * Check if an element with the present index is set.
     *
     * @param   string  $index      The index of the element to search.
     * @return  bool                True if an element with the present index
     *                              exists.
     */
    public function has(string $index);

    /**
     * Set an element with the present index and value.
     *
     * @param   string  $index      The index of the element to add/change. An
     *                              existing element with the present index will
     *                              be overridden.
     * @param   type    $value      The value to set.
     * @return  void
     */
    public function set(string $index, $value);

    /**
     * Set all elements of the present model.
     *
     * @param   array   $all        The elements to set.
     * @return  void
     */
    public function setAll(array $all);
}
