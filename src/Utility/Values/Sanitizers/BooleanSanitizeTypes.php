<?php

namespace Ht7\Kernel\Utility\Values\Sanitizers;

use \Ht7\Base\Enum;

/**
 * This enum provides flags for boolean sanitizers, which implement
 * Ht7\Kernel\Utility\Values\Sanitizers\Sanitizerable.
 *
 * This is not a bitmask. That means, only one flag be choosen on a method call.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class BooleanSanitizeTypes extends Enum
{

    /**
     * Return following strins: "false" and "true".
     */
    const TRUE_FALSE_LOWERCASE = 1;

    /**
     * Return following strins: "FALSE" and "TRUE".
     */
    const TRUE_FALSE_UPPERCASE = 2;

    /**
     * Return following integers: 0 for false and 1 for true.
     */
    const NUMERICAL = 3;

}
