<?php

namespace Ht7\Kernel\Utility\Values\Sanitizers;

use \InvalidArgumentException;
use \Ht7\Base\Lists\ItemList;
use \Ht7\Kernel\Utility\Values\Sanitizers\Sanitizerable;

/**
 * The SanitizerList holds the sanitizers.
 *
 * Example:<br />
 * <code>
 * use \Ht7\Kernel\Utility\Values\Sanitizers\SanitizerList;<br />
 * use \Ht7\Kernel\Utility\Values\Sanitizers\BooleanSanitizeTypes;<br />
 * use \Ht7\Kernel\Utility\Values\Sanitizers\BooleanSanitizer;<br />
 * use \Ht7\Kernel\Utility\Values\Sanitizers\StringSanitizeTypes;<br />
 * use \Ht7\Kernel\Utility\Values\Sanitizers\StringSanitizer;<br />
 *
 * $items = [<br />
 * &nbsp;&nbsp;&nbsp;&nbsp;[(new BooleanSanitizer()), BooleanSanitizeTypes::TRUE_FALSE_LOWERCASE],<br />
 * &nbsp;&nbsp;&nbsp;&nbsp;[(new StringSanitizer()), StringSanitizeTypes::ALL]<br />
 * ];
 *
 * $sL = new SanitizerList($items);<br />
 * $value = 'To sanitize...';<br />
 * // Call the string sanitizer:<br />
 * $sL->sanitize($value);
 * </code>
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class SanitizerList extends ItemList
{

    /**
     * These flags will be added to the method call of <code>sanitize($value, int $flags)</code>
     * and have to be in the same order as the sanitizers.
     *
     * @var     array               Indexed array of flags (integers)
     */
    protected $flags;

    /**
     * A sanitizer is responsible for one specific data type. It makes defined
     * sanitasations on the submitted element.
     *
     * @var     array               Indexed array of sanitizers. Multiple sanitizers
     *                              serving the same data type is supported.
     */
    protected $items;

    /**
     * Create an instance of the <code>SanitizerList</code>.
     *
     * @param   array   $data       The items to add to the list. This must be an
     *                              indexed array of indexed arrays, where the
     *                              first item has to be the sanitizer and the
     *                              second its flags.
     */
    public function __construct(array $data = [])
    {
        $this->flags = [];

        parent::__construct($data);
    }

    /**
     * {@inheritdoc}
     *
     * When calling this method explicit, do not forget to add a flag too.
     * (<code>$this->addFlag($flag);</code>)
     *
     * @param   Sanitizerable   $item       A sanitizerable implementation.
     * @throws  InvalidArgumentException
     * @todo                                Implementation of adding a class definition.
     */
    public function add($item)
    {
        if ((!is_string($item) || !is_object($item)) && !in_array(Sanitizerable::class, class_implements($item))) {
            $e = 'An element of the SanitizerList has to be an instance of'
                    . ' an implementor of the \Ht7\Kernel\Utility\Values\Sanitizers\Sanitizerable'
                    . ' interface or a full qualified string of such a class.';

            throw new InvalidArgumentException($e);
        }

        return parent::add($item);
    }

    /**
     * Add a flag.
     *
     * This flag will be used as second paramter when calling the sanitize-method
     * of the certain sanitizer. Therefor make sure the sequence of the sanitizers
     * is the same as the flag sequence.
     *
     * @param   int     $flag
     * @return  void
     */
    public function addFlag(int $flag)
    {
        $this->flags[] = $flag;
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $data)
    {
        foreach ($data as $el) {
            if (is_array($el)) {
                $this->add($el[0]);

                if (is_array($el[1])) {
                    $this->addFlag(
                            array_reduce(
                                    $el[1],
                                    function($carry, $item) {
                                return $carry | $item;
                            })
                    );
                } else {
                    $this->addFlag($el[1]);
                }
            } else {
                $this->add($el);
                $this->addFlag(0);
            }
        }
    }

    /**
     * Iterate through all present sanitizers and let it sanitize, where definied.
     *
     * @param   mixed   $value          The value to sanitize.
     * @return  string                  The sanitized value as string representation.
     */
    public function sanitize($value)
    {
        foreach ($this as $key => $sanitizer) {
            if ($sanitizer->is($value)) {
                return $sanitizer->sanitize($value, $this->flags[$key]);
            }
        }
    }

}
