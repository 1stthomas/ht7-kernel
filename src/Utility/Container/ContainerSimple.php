<?php

namespace Ht7\Kernel\Utility\Container;

use \Exception;
use \Ht7\Kernel\Utility\Container\CmsContainerable;
use \Psr\Container\NotFoundExceptionInterface;

/**
 * Description of Container
 *
 * @author Thomas Pluess
 */
class ContainerSimple implements CmsContainerable
{

    protected $bindings;
    protected $instances;
    protected static $instance;

    public function __construct()
    {
        $this->bindings = [];
        $this->instances = [];
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * {@inheritdoc}
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * {@inheritdoc}
     */
    public function bound($abstract)
    {
        return in_array($abstract, array_keys($this->getBindings()));
    }

    /**
     * Get the binding of the present abstract.
     *
     * {@inheritdoc}
     */
    public function get($abstract)
    {
        try {
            return $this->getBindings()[$abstract];
        } catch (Exception $e) {
            if ($this->has($abstract)) {
                $class = $this->get('exc/container/psr/container');
                throw new $class($abstract, $e->getCode(), $e);
            } else {
                $class = $this->get('exc/container/psr/notfound');
                throw new $class($abstract, $e->getCode(), $e);
            }
        }
    }

    public function getBindings()
    {
        return $this->bindings;
    }

    public function getInstances()
    {
        return $this->instances;
    }

    /**
     * {@inheritdoc}
     */
    public function has($abstract)
    {
        return array_key_exists($abstract, $this->getBindings());
    }

    /**
     * {@inheritdoc}
     */
    public function instance($abstract, $instance)
    {
        $this->bind($abstract, get_class($instance), true);

        $this->instances[$abstract] = $instance;
    }

    /**
     * {@inheritdoc}
     */
    public function make($abstract, $parameters = [])
    {
        return $this->resolve($abstract, $parameters);
    }

    public function reset()
    {
        $this->bindings = [];
        $this->singletons = [];
    }

    public function resolve($abstract, $parameters = [])
    {
        if ($this->has($abstract)) {
            $instances = $this->getInstances();

            if (array_key_exists($abstract, $instances)) {
                return $instances[$abstract];
            } else {
                $class = $this->getBindings()[$abstract];

                if (!count($parameters)) {
                    $instances[$abstract] = new $class();
                } elseif (count($parameters) === 1) {
                    $instances[$abstract] = new $class($parameters[0]);
                } else {
                    $instances[$abstract] = new $class(...$parameters);
                }
            }
        } else {

        }
    }

    /**
     * {@inheritdoc}
     */
    public function singleton($abstract, $concrete = null)
    {
        $this->bind($abstract, $concrete, true);
    }

}
