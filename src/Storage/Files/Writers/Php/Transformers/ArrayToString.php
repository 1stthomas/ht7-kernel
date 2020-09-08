<?php

namespace Ht7\Kernel\Storage\Files\Writers\Php\Transformers;

use \Ht7\Kernel\Storage\Files\Writers\Php\Transformers\ArrayToStringable;
use \Ht7\Kernel\Storage\Files\Writers\Php\Transformers\BaseTransformerOptions;
use \Ht7\Kernel\Storage\Files\Writers\Php\PhpStringWriterOptionFlags;
use \Ht7\Kernel\Utility\Values\Sanitizers\SanitizerList;

/**
 * An instance of this class can transform a PHP array into its string representation.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class ArrayToString implements ArrayToStringable
{

    /**
     * @var     int                 The indent per level of the arrays.
     */
    protected $indent;

    /**
     * @var     BaseTransformerOptions  The tranformation options.
     */
    protected $options;

    /**
     * @var     SanitizerList       An iterable list of sanitizers.
     */
    protected $sanitizers;

    /**
     * Create an instance of the <code>ArrayToString</code> transformer class.
     *
     * @param   BaseTransformerOptions  $options    The transformation options.
     * @param   SanitizerList           $sanitizers A list with the sanitizers
     *                                              to use.
     */
    public function __construct(BaseTransformerOptions $options, SanitizerList $sanitizers)
    {
        $this->setOptions($options);
        $this->setSanitizers($sanitizers);
    }

    /**
     * {@inheritdoc}
     */
    public function createArrayEnd(int $level, bool $addIndent = true)
    {
        return ($addIndent ? $this->getIndentCurrent($level) : '') . '],' . PHP_EOL;
    }

    /**
     * Create an array arrow.
     *
     * @return  string                      The arrow with surrouding spaces.
     */
    public function createArrow()
    {
        return ' => ';
    }

    /**
     * {@inheritdoc}
     */
    public function createFileEnd()
    {
        return '];' . PHP_EOL;
    }

    /**
     * {@inheritdoc}
     */
    public function createFileStart()
    {
        $content = '';

        if ($this->getOptions()->getFlags() & PhpStringWriterOptionFlags::HAS_OPENING_PHP_TAG) {
            $content .= '<?php' . PHP_EOL;
        }

        if ($this->getOptions()->getFlags() & PhpStringWriterOptionFlags::HAS_EMPTY_LINE_AFTER_OPENING) {
            $content .= PHP_EOL;
        }

        $content .= 'return [' . PHP_EOL;

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function createLine($value, int $level, $key = null)
    {
        $line = $this->getIndentCurrent($level);

        if (!empty($key)) {
            $line .= $this->sanitizeValue($key);
            $line .= $this->createArrow();
        }

        if (is_array($value)) {
            $line .= '[';

            if (!empty($value)) {
                $line .= PHP_EOL;
            }
        } else {
            $line .= $this->sanitizeValue($value) . ',' . PHP_EOL;
        }

        return $line;
    }

    /**
     * Get the transformation options.
     *
     * @return  BaseTransformerOptions      The transformation options.
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get the sanitizer list.
     *
     * @return  SanitizerList               A list with the assigned sanitizers.
     */
    public function getSanitizers()
    {
        return $this->sanitizers;
    }

    /**
     * Sanitize an array key or value.
     *
     * This method iterates through the defined sanitizers, checks if a data type
     * of the present value corresponds to a sanitizer. If a sanitizer could be
     * found, the sanitizer will sanitize the value. Otherwise the input value
     * will be returned.
     *
     * Supported data types:
     * - boolean
     * - integer
     * - float
     * - string
     *
     * @param   mixed   $value              The value to sanitize.
     * @return  mixed                       One of the supported data types.
     */
    public function sanitizeValue($value)
    {
        return $this->getSanitizers()->sanitize($value);
    }

    /**
     * Set the transformation options.
     *
     * @param   BaseTransformerOptions  $options    The transformation options.
     * @return  void
     */
    public function setOptions(BaseTransformerOptions $options)
    {
        $this->options = $options;
        $this->indent = null;
    }

    /**
     * Set the list with the sanitizers to use.
     *
     * @param   SanitizerList   $sanitizers     A list with sanitizer instances
     *                              To be used during the transformation.
     * @return  void
     */
    public function setSanitizers(SanitizerList $sanitizers)
    {
        $this->sanitizers = $sanitizers;
    }

    /**
     * Get the indention string of the present code level.
     *
     * @param   int     $level      The present code level.
     * @return  string              The string of the current indention.
     */
    protected function getIndentCurrent(int $level)
    {
        $indentString = $this->getIndentString();
        $indent = '';

        while ($level > 0) {
            $indent .= $indentString;
            $level--;
        }

        return $indent;
    }

    /**
     * Get the indention string of one code level.
     *
     * @return  string                      The indention as a string.
     */
    protected function getIndentString()
    {
        if ($this->indent === null) {
            $this->indent = '';
            $indentNumber = $this->getOptions()->getIndention();

            while ($indentNumber > 0) {
                $this->indent .= ' ';
                $indentNumber--;
            }
        }

        return $this->indent;
    }

}
