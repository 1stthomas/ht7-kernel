<?php

namespace Ht7\Kernel\Config;

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
    const KERNEL = 1;

    /**
     * The config was read out of the app structure.
     */
    const APP = 2;

    /**
     * The config was read out of the addon structure.
     */
    const OVERRIDE = 3;

    /**
     * The config was read out of the addon structure.
     */
    const CACHE = 4;

    /**
     * The config was read out of the addon structure.
     */
    const ADDON = 5;

    /**
     * The config was read out of the cms structure.
     */
    const CMS = 6;

    public static function getAsString(int $constant)
    {
        return '' . $constant;
    }

    public static function getAsStringByName(string $constant)
    {
        return self::getAsString(self::getConstant(strtoupper($constant)));
    }

    public static function getNameByConstant(int $constant)
    {
        $names = ['all', 'kernel', 'app', 'override', 'cache', 'addon', 'cms'];

        return $names[$constant];
    }

}
