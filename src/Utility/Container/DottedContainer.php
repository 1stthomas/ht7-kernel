<?php

namespace Ht7\Kernel\Utility\Container;

use \Ht7\Kernel\Utility\Container\ContainerDottable;

/**
 * Description of AbstractCategoryContainer
 *
 * @author Thomas Pluess
 */
class DottedContainer implements ContainerDottable
{

    protected $elements;
    protected $class;
    protected $id;

    public function __construct(string $class)
    {
        $this->class = $class;
        $this->elements = [];
    }

    public function add($element)
    {
        array_unshift($this->elements, $element);
    }

    public function append($element)
    {
        $this->elements[] = $element;
    }

    public function get($index, $default = null)
    {
        foreach ($this->elements as $element) {
            if ($element->has($index)) {
                return $element->get($index);
            }
        }

        return $default;
    }

    public function getId()
    {
        return $this->id;
    }

    public function has($index)
    {
        foreach ($this->elements as $element) {
            if ($element->has($index)) {
                return true;
            }
        }

        return false;
    }

    public function rGet(string $index, $default = null)
    {
        $this->elements = array_reverse($this->elements);
        $item = $this->get($index, $default);
        $this->elements = array_reverse($this->elements);

        return $item;
    }

    public function setId(string $id)
    {
        $this->id = $id;
    }

}
