<?php

namespace Ht7\Kernel\Utility\Container;

use \Psr\Container\ContainerInterface;

/**
 *
 * @author Thomas Pluess
 */
interface CmsContainerable extends ContainerInterface
{

    /**
     * Register a binding with the container.
     *
     * @param  string  $abstract
     * @param  \Closure|string|null  $concrete
     * @param  bool  $shared
     * @return void
     */
    public function bind($abstract, $concrete = null, $shared = false);

    /**
     * Determine if the given abstract type has been bound.
     *
     * @param  string  $abstract
     * @return bool
     */
    public function bound($abstract);

    /**
     * Register an existing instance as shared in the container.
     *
     * @param  string  $abstract
     * @param  mixed  $instance
     * @return mixed
     */
    public function instance($abstract, $instance);

    /**
     * Register a shared binding in the container.
     *
     * @param  string  $abstract
     * @param  \Closure|string|null  $concrete
     * @return void
     */
    public function singleton($abstract, $concrete = null);
}
