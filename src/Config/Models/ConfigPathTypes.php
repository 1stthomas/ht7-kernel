<?php

namespace Ht7\Kernel\Config\Models;

use \Ht7\Base\Enum;

/**
 * Description of RowTypes
 *
 * @author Thomas Pluess
 */
class ConfigPathTypes extends Enum
{

    /**
     * All defined config types.
     */
    const ALL = 0;

    /**
     * The config was read out of the cms structure.
     */
    const CMS = 1;

    /**
     * The config was read out of the app structure.
     */
    const APP = 2;

    /**
     * The config was read out of the addon structure.
     */
    const ADDON = 3;

    /**
     * The config was read out of the addon structure.
     */
    const CACHE = 4;

}
