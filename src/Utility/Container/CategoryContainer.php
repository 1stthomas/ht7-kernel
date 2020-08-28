<?php

namespace Ht7\Kernel\Utility\Container;

use \Ht7\Kernel\Utility\Container\ContainerCategorable;

/**
 * Description of AbstractCategoryContainer
 *
 * @author Thomas Pluess
 */
class CategoryContainer implements ContainerCategorable
{

    protected $categories;

    /**
     * {@inheritdoc}
     */
    public function get($index, $default = null)
    {
        $parts = explode('.', $index);
//        $category = array_shift($parts);
//        $indexNew = implode('.', $parts);

        $config = $this->getCategory(array_shift($parts));

//        return $config->get($indexNew, $default);
        return $config->get(implode('.', $parts), $default);
    }

    /**
     * {@inheritdoc}
     */
    public function getCategory($category)
    {
        return $this->categories[$category];
    }

    /**
     * {@inheritdoc}
     */
    public function has($index)
    {
        $parts = explode('.', $index);
//        $type = array_shift($parts);
//        $indexNew = implode('.', $parts);

        $config = $this->getCategory(array_shift($parts));

        return $config->has(implode('.', $parts));
    }

}
