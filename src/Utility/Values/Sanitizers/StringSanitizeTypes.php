<?php

namespace Ht7\Kernel\Utility\Values\Sanitizers;

use \Ht7\Base\Enum;

/**
 * This enum provides a bitmask for string sanitizers, which implement
 * Ht7\Kernel\Utility\Values\Sanitizers\Sanitizerable.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class StringSanitizeTypes extends Enum
{

    /**
     * Apply all option flags.
     */
    const ALL = 0;

    /**
     * Add quotation marks around the element.
     */
    const ADD_QUOTATION_MARKS = 1;

    /**
     * Change illegal single char quotation marks into the defined legal one.
     */
    const SANITIZE_QUOTATION_MARKS = 2;

    /**
     * Encode single chars which are quotation marks.
     */
    const ENCODE_QUOTATION_MARKS = 4;

    /**
     * When class defintions like <code>Ht7\Kernel\Kernel::class</code> are
     * transformed into a string, the defintion will turn into the string
     * <code>"Ht7\Kernel\Kernel"</code>. Setting this flag will prevent from
     * this transformation.
     */
    const KEEP_CLASS_DEFINITIONS = 8;

}
