<?php

namespace Ht7\Kernel\Storage\Files\Writers\Php\Transformers;

use \Ht7\Kernel\Storage\Files\Writers\Php\Transformers\ArrayToStringable;
use \Ht7\Kernel\Storage\Files\Writers\Php\Transformers\BaseTransformerOptions;
use \Ht7\Kernel\Storage\Files\Writers\Php\PhpStringWriterOptionFlags;
use \Ht7\Kernel\Utility\Values\Sanitizers\SanitizerList;

/**
 * An instance of this class can transform a PHP array into its string representation.
 *
 * @author Thomas Pluess
 */
class ArrayToString implements ArrayToStringable
{

    /**
     *
     * @var     int                 The indent per level of the arrays.
     */
    protected $indent;

    /**
     *
     * @var     BaseTransformerOptions
     */
    protected $options;

    /**
     * @var     SanitizerList       An iterable list of sanitizers.
     */
    protected $sanitizers;

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

    public function getOptions()
    {
        return $this->options;
    }

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
        foreach ($this->getSanitizers() as $sanitizer) {
            if ($sanitizer->is($value)) {
                return $sanitizer->sanitize($value);
            }
        }

        return $value;
    }

    public function setOptions(BaseTransformerOptions $options)
    {
        $this->options = $options;
        $this->indent = null;
    }

    public function setSanitizers(SanitizerList $sanitizers)
    {
        $this->sanitizers = $sanitizers;
    }

}
