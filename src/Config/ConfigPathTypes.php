<?php

namespace Ht7\Kernel\Config;

use \Ht7\Base\Enum;

/**
 * This enum defines all supported config path types.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class ConfigPathTypes extends Enum
{

    /**
     * All defined config types.
     */
    const ALL = 'all';

    /**
     * The config was read out of the cms structure.
     */
    const KERNEL = 'kernel';

    /**
     * The config was read out of the app structure.
     */
    const APP = 'app';

    /**
     * The config was read out of the addon structure.
     */
    const OVERRIDE = 'override';

    /**
     * The config was read out of the addon structure.
     */
    const CACHE = 'cache';

    /**
     * The config was read out of the addon structure.
     */
    const ADDON = 'addon';

}
