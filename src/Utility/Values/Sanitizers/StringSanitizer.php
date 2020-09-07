<?php

namespace Ht7\Kernel\Utility\Values\Sanitizers;

use \Ht7\Kernel\Utility\Values\Sanitizers\Sanitizerable;
use \Ht7\Kernel\Utility\Values\Sanitizers\StringSanitizeTypes;

/**
 * Sanitizer for string values.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class StringSanitizer implements Sanitizerable
{

    /**
     * @var     array               An assoc array of options. Only used index:
     *                              'quotation_mark'.
     */
    protected $options;

    /**
     * Create an instance of the <code>StringSanitizer</code> class.
     *
     * @param   array   $options    An assoc array with an element with key
     *                              'quotation_mark' and the desired char.
     */
    public function __construct(array $options)
    {
        $this->setOptions($options);
    }

    /**
     * Get a specific option by its name.
     *
     * @param   string  $index      The name of the option to retrieve.
     * @return  mixed               The corresponding value.
     */
    public function getOption(string $index)
    {
        return $this->options[$index];
    }

    /**
     * Get all defined options.
     *
     * @return  array               Assoc array of options with the keys as option
     *                              names.
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'string';
    }

    /**
     * Check if the element is a string.
     *
     * {@inheritdoc}
     *
     * @return  bool                    True if the element is a string.
     */
    public function is($element)
    {
        return is_string($element);
    }

    /**
     * Sanitize a string.
     *
     * The behavior of this method can be changed by the bitmask of the enum
     * Ht7\Kernel\Utility\Values\Sanitizers\StringSanitizeType.
     *
     * {@inheritdoc}
     *
     * @param   int         $flags          Additional flags to control the behavior
     *                                      of the sanitation method. Default:
     *                                      <code>Ht7\Kernel\Utility\Values\Sanitizers\StringSanitizeTypes::ALL</code>
     * @return  string                  The sanitized value according to the
     *                                  bitmask.
     */
    public function sanitize($element, int $flags = StringSanitizeTypes::ALL)
    {
        // Sanitize and go back when the condition meets.
        if ($flags === 0 || $flags & StringSanitizeTypes::KEEP_CLASS_DEFINITIONS) {
            if (class_exists($element)) {
                return $element . '::class';
            }
        }

        // Return at the end.
        if ($flags === 0 || $flags & StringSanitizeTypes::SANITIZE_QUOTATION_MARKS) {
            if (in_array($element, ['`', '´'])) {
                $element = $this->getOption('quotation_mark');
            }
        }
        if ($flags === 0 || $flags & StringSanitizeTypes::ENCODE_QUOTATION_MARKS) {
            if (in_array($element, ['"', "'"]) && $element === $this->getOption('quotation_mark')) {
                $element = '\\' . $element;
            }
        }
        if ($flags === 0 || $flags & StringSanitizeTypes::ADD_QUOTATION_MARKS) {
            $element = $this->getOption('quotation_mark')
                    . $element
                    . $this->getOption('quotation_mark');
        }

        return $element;
    }

    /**
     * Set the options.
     *
     * @param   array   $options            The options as assoc array.
     * @return  void
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

}
