<?php

namespace Ht7\Kernel\Utility\Values\Sanitizers;

/**
 * This interface describes the necessary methods for a class, which can sanitize
 * certain data types.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
interface Sanitizerable
{

    /**
     * Get the data type of the supported sanitation.
     *
     * @return  string                      The data type which the current
     *                                      sanitizer supports.
     */
    public function getType();

    /**
     * Check if the element is from the data type which the current sanitizer supports.
     *
     * @param   mixed       $element        The element to check.
     * @return  bool                        True if the present element is from
     *                                      a data type which the current sanitizer
     *                                      supports.
     */
    public function is($element);

    /**
     * Sanitize a value of a certain data type.
     *
     * @param   mixed       $element        The element to sanitize.
     * @param   int         $flags          Additional flags to control the behavior
     *                                      of the sanitation method. Default: 0.
     * @return  mixed                       The sanitized value.
     */
    public function sanitize($element, int $flags = 0);
}
