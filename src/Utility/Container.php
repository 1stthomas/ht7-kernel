<?php

namespace Ht7\Kernel\Utility;

/**
 * Description of Container
 *
 * @author Thomas Pluess
 */
class Container
{

    protected $overrides;
    protected $singletons;
    protected $singletonsDef;
    protected static $instance;

    public function __construct()
    {
        $this->singletons = [];
        $this->singletonsDef = [];
    }

    public function addOverride($class, $classNew)
    {
        $this->overrides[$class] = $classNew;
    }

    public function addSingleton($key, $target)
    {
        $this->singletonsDef[$key] = $target;
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function hasOverride($class)
    {
        return isset($this->overrides[$class]);
    }

    public function hasSingleton($key)
    {
        return isset($this->singletons[$key]);
    }

    public function make($key, $params = [])
    {
        if (!$this->hasSingleton($key)) {
            $class = isset($this->overrides[$key]) ? $this->overrides[$key] : $this->singletonsDef[$key];

            if (!count($params)) {
                $this->singletons[$key] = new $class();
            } elseif (count($params) === 1) {
                $this->singletons[$key] = new $class($params[0]);
            } else {
                $this->singletons[$key] = new $class(...$params);
            }
        }

        return $this->singletons[$key];
    }

    public function reset()
    {
        $this->overrides = [];
        $this->singletons = [];
        $this->singletonsDef = [];
    }

}
