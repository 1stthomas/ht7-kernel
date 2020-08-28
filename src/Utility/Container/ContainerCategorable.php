<?php

namespace Ht7\Kernel\Utility\Container;

/**
 *
 * @author Thomas Pluess
 */
interface ContainerCategorable
{

    /**
     * Get the element with the present index or return a default value.
     *
     * @param type $index
     * @param type $default
     */
    public function get($index, $default = null);

    /**
     * Get the the category.
     *
     * @param   mixed   $category   The name of category to retrieve.
     */
    public function getCategory($category);

    /**
     * Return wheter there is an element with the present index or not.
     *
     * @param   mixed   $index      The index of the element to find.
     */
    public function has($index);
}
