<?php

namespace Ht7\Kernel\Storage\Files\Writers\Php;

use \Ht7\Base\Enum;

/**
 * This enum provides some flags to change the creation of a PHP string.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class PhpStringWriterOptionFlags extends Enum
{

    /**
     * Apply all option flags.
     */
    const ALL = 0;

    /**
     * Set this flag to add an opening php tag at the begin of the array string.
     */
    const HAS_OPENING_PHP_TAG = 1;

    /**
     * Add this flag to add an empty line after the opening PHP tag. This flag
     * can also be used without the <code>HAS_OPENING_PHP_TAG</code> flag. In
     * this case, the first line of the string will be empty.
     */
    const HAS_EMPTY_LINE_AFTER_OPENING = 2;

}
