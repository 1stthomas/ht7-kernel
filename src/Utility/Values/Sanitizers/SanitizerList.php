<?php

namespace Ht7\Kernel\Utility\Values\Sanitizers;

use \InvalidArgumentException;
use \Ht7\Base\Lists\ItemList;
use \Ht7\Kernel\Utility\Values\Sanitizers\Sanitizerable;
use \Ht7\Kernel\Utility\Values\Sanitizers\SanitizerListItem;

/**
 * The SanitizerList holds the sanitizers resp. the SanitizerListItems.
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
     *                              second its flags or an indexed array of
     *                              <code>SanitizerListItem</code> instances.
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    /**
     * {@inheritdoc}
     *
     * @param   SanitizerListItem   $item   Simple model with the sanitizer and
     *                                      the flags.
     * @throws  InvalidArgumentException
     */
    public function add($item)
    {
        if (!is_object($item) || !($item instanceof SanitizerListItem)) {
            $e = 'An element of the SanitizerList has to be an instance of the'
                    . ' \Ht7\Kernel\Utility\Values\Sanitizers\SanitizerListItem'
                    . ' class.';

            throw new InvalidArgumentException($e);
        }

        return parent::add($item);
    }

    public function addIndividually(Sanitizerable $sanitizer, $flags)
    {
        if (is_array($flags)) {
            $flags = array_reduce(
                    $flags,
                    function($carry, $item) {
                return $carry | $item;
            });
        }

        $this->add((new SanitizerListItem($sanitizer, $flags)));
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $data)
    {
        foreach ($data as $el) {
            if (is_array($el)) {
                $this->addIndividually($el[0], $el[1]);
            } else {
                $this->add($el);
            }
        }
    }

    /**
     * Iterate through all present sanitizers and let it sanitize, where the
     * condition is fullfield.
     *
     * @param   mixed   $value          The value to sanitize.
     * @return  string                  The sanitized value as string representation.
     */
    public function sanitize($value)
    {
        foreach ($this as $listItem) {
            if ($listItem->getSanitizer()->is($value)) {
                return $listItem
                                ->getSanitizer()
                                ->sanitize($value, $listItem->getFlags());
            }
        }
    }

}
