<?php

namespace Ht7\Kernel\Utility;

/**
 * Description of Registry
 *
 * @author Thomas Pluess
 */
class Registry
{

    protected $data;
    protected $instances;
    protected $instanciated;

    public function __construct(array $classes = [], array $instances = [], $data = null)
    {
        $this->setClasses($classes);
        $this->setInstances($instances);
        $this->setData($data);
    }

    public function add(object $instance)
    {
        $class = get_class($instance);
        $key = array_search($class, $this->instances);

        if ($key !== false) {
            unset($this->instances[$key]);
        }

        $this->instances[$class] = $instance;
    }

    public function getAll()
    {
        return $this->instances;
    }

    public function getClasses()
    {
        return array_map(function($k, $v) {
            if (is_numeric($k)) {
                return [$v];
            } else {
                return [$k];
            }
        },
                array_keys($this->instances),
                $this->instances
        );
    }

    public function getData()
    {
        return $this->data;
    }

    public function getInstance(string $class)
    {
        if (!array_key_exists($class, $this->instances)) {
            $this->add((new $class($this->getData())));
        }

        return $this->instances[$class];
    }

    public function getInstances()
    {
        return array_filter(
                $this->instances,
                function($k) {
            return !is_numeric($k);
        },
                ARRAY_FILTER_USE_KEY);
    }

    public function initialise()
    {
        $classes = $this->getClasses();

        array_walk(
                $classes,
                function($class) {
            $this->add($this->getInstance($class));
        });
    }

    public function register(string $class)
    {
        $this->instances[] = $class;
    }

    public function registerMultiple(array $sanitizers)
    {
        array_walk($sanitizers, [$this, 'register']);
    }

    public function setData($data)
    {
        $this->data = $data;
    }

}
