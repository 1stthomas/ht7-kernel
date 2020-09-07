<?php

namespace Ht7\Kernel\Utility\Values\Sanitizers;

use \Ht7\Kernel\Utility\Values\Sanitizers\BooleanSanitizeTypes;
use \Ht7\Kernel\Utility\Values\Sanitizers\Sanitizerable;

/**
 * This class does nothing more than return the original value.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class DefaultSanitizer implements Sanitizerable
{

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'default';
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
        return true;
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
    public function sanitize($element, int $flags = 0)
    {
        return $element;
    }

}
