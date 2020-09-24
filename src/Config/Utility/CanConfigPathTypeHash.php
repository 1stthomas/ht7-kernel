<?php

namespace Ht7\Kernel\Config\Utility;

/**
 *
 * @author Thomas Pluess
 */
trait CanConfigPathTypeHash
{

    /**
     * @var     string                      The config path type.
     */
    protected $configPathType;

    /*     * Get the config path type.
     *
     * @return  string                      The config path type.
     */

    public function getHash()
    {
        return $this->configPathType;
    }

}
