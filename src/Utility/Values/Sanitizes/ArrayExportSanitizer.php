<?php

namespace Ht7\Kernel\Utility\Values\Sanitizes;

use \Ht7\Kernel\Export\Files\ExportOptions;
use \Ht7\Kernel\Storage\Files\FileExtensions;

/**
 * Description of ArrayExportSanitizer
 *
 * @author Thomas Pluess
 */
class ArrayExportSanitizer
{

    protected $indent;

    /**
     *
     * @var     ExportOptions
     */
    protected $options;

    public function __construct(ExportOptions $options)
    {
        $this->setOptions($options);
    }

    public function closeArray()
    {
        return '],' . PHP_EOL;
    }

    public function createArrow()
    {
        return ' => ';
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
            $indentNumber = $this->getOptions()
                    ->get('extensions.' . FileExtensions::PHP . '.indention');

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

//    public function isQuotationMark(string $value)
//    {
//        return in_array($value, ['"', "'"]);
//    }

    public function sanitizeKey($key)
    {
        return $this->getOptions()
                        ->get('extensions.' . FileExtensions::PHP . '.quotation_mark')
                . $key
                . $this->getOptions()
                        ->get('extensions.' . FileExtensions::PHP . '.quotation_mark');
    }

    public function sanitizeLine($value, $level, $key = null)
    {
        $line = $this->getIndentCurrent($level);

        if (!empty($key)) {
            $line .= $this->sanitizeKey($key);
            $line .= $this->createArrow();
        }

        if (is_array($value)) {
            $line .= '[';
        } else {
            $line .= $this->sanitizeValue($value) . "," . PHP_EOL;
        }

        return $line;
    }

    public function sanitizeValue($value)
    {
        $qm = $this->getOptions()
                ->get('extensions.' . FileExtensions::PHP . '.quotation_mark');
//        $qm = $this->getQuotationMark();

        if (is_string($value)) {
            if (strlen($value) === 0) {
                return '';
            } elseif (strlen($value) === 1) {
                if ($value === '`') {
                    $value = '\'';
                }

                if ($this->isQuotationMark($value)) {
                    return $qm . '\\' . $value . $qm;
                } else {

                }
            } elseif (strlen($value) === 2) {

            } else {

            }
            return $qm . $value . $qm;
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        } else {
            return $value;
        }
    }

    public function setOptions(ExportOptions $options)
    {
        $this->options = $options;
        $this->indent = null;
    }

//    public function setQuotationMark(string $qm)
//    {
//        $this->quotationMark = $qm;
//    }
}
