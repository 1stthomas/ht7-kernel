<?php

namespace Ht7\Kernel\Utility\Values\Sanitizers;

use \Ht7\Kernel\Utility\Values\Sanitizers\BooleanSanitizeTypes;
use \Ht7\Kernel\Utility\Values\Sanitizers\Sanitizerable;

/**
 * This class sanitizes boolean values to their string representation.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class BooleanSanitizer implements Sanitizerable
{

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'bool';
    }

    /**
     * Check if the element is a boolean.
     *
     * {@inheritdoc}
     *
     * @return  bool                    True if the element is a boolean.
     */
    public function is($element)
    {
        return is_bool($element);
    }

    /**
     * Sanitize a boolean.
     *
     * This method checks if the element evaluates to true and returns the
     * sanitized value.
     *
     * {@inheritdoc}
     *
     * @param   int     $flags          Additional flags to control the behavior
     *                                  of the sanitation method. Default:
     *                                  BooleanSanitizeTypes::TRUE_FALSE_LOWERCASE.
     * @return  string                  The sanitized value according to the
     *                                  flag.
     */
    public function sanitize($element, int $flags = BooleanSanitizeTypes::TRUE_FALSE_LOWERCASE)
    {
        if ($flags === BooleanSanitizeTypes::TRUE_FALSE_LOWERCASE) {
            return $element ? 'true' : 'false';
        } elseif ($flags === BooleanSanitizeTypes::TRUE_FALSE_UPPERCASE) {
            return $element ? 'TRUE' : 'FALSE';
        } elseif ($flags === BooleanSanitizeTypes::NUMERICAL) {
            return $element ? 1 : 0;
        }
    }

}
