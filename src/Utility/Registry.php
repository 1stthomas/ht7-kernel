<?php

namespace Ht7\Kernel\Utility;

/**
 * Description of Registry
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 * @copyright   (c) 2020 Thomas Pluess
 */
class Registry
{

    protected $data;
    protected $instances;
    protected $instanciated;

    public function __construct(array $classesOrIinstances = [], $data = null)
    {
        $this->registerMultiple($classesOrIinstances);
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
        return array_filter(
                $this->instances,
                function($k) {
            return is_numeric($k);
        },
                ARRAY_FILTER_USE_KEY
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
                ARRAY_FILTER_USE_KEY
        );
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

    public function register($classOrInstance)
    {
        if (is_string($classOrInstance)) {
            $this->instances[] = $classOrInstance;
        } else {
            $this->instances[get_class($classOrInstance)] = $classOrInstance;
        }
    }

    public function registerMultiple(array $classesOrInstances)
    {
        array_walk($classesOrInstances, [$this, 'register']);
    }

    public function setData($data)
    {
        $this->data = $data;
    }

}
