<?php

namespace Ht7\Kernel\Utility\Values\Sanitizers;

use \Ht7\Kernel\Utility\Values\Sanitizers\Sanitizerable;

/**
 * Simple model for the <code>SanitizerList</code>.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class SanitizerListItem
{

    /**
     * @var     Sanitizerable               An object which can sanitize a certain
     *                                      data type.
     */
    protected $sanitizer;

    /**
     * This flag will be used as second paramter when calling the sanitize-method
     * of the certain sanitizer.
     *
     * @var     int                         The flag or the resulting bitmask
     *                                      which will be the second parameter
     *                                      of the <code>sanitize()</code> call.
     */
    protected $flags;

    /**
     * Create an instance of the <code>SanitizerListItem</code> class.
     *
     * @param   Sanitizerable   $sanitizer  The sanitizer.
     * @param   int             $flags      The flags.
     */
    public function __construct(Sanitizerable $sanitizer, int $flags)
    {
        $this->setFlags($flags);
        $this->setSanitizer($sanitizer);
    }

    /**
     * Get the flags.
     *
     * @return  int                         The flags which will be the second
     *                                      parameter of the sanitize method call.
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * Get the sanitizer.
     *
     * @return  Sanitizerable               The definied sanitizer.
     */
    public function getSanitizer()
    {
        return $this->sanitizer;
    }

    /**
     * Set the flags of the present sanitizer.
     *
     * @param   int     $flags              The flags which will be the second
     *                                      parameter of the sanitize method call.
     */
    public function setFlags(int $flags)
    {
        $this->flags = $flags;
    }

    /**
     * Set the sanitizer.
     *
     * @param   Sanitizerable   $sanitizer  The sanitizer.
     */
    public function setSanitizer(Sanitizerable $sanitizer)
    {
        $this->sanitizer = $sanitizer;
    }

}
