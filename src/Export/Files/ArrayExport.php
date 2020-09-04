<?php

namespace Ht7\Kernel\Export\Files;

use \RuntimeException;
use \Ht7\Kernel\Export\Exportable;
use \Ht7\Kernel\Export\Files\ExportOptions;
use \Ht7\Kernel\Storage\Files\FileExtensions;
use \Ht7\Kernel\Utility\Values\Sanitizes\ArrayExportSanitizer;

/**
 * Description of Array
 *
 * @author Thomas Pluess
 */
class ArrayExport implements Exportable
{

    protected $content;
    protected $data;
    protected $options;

    /**
     *
     * @var     ArrayExportSanitizer
     */
    protected $sanitizer;
    protected $target;

    /**
     * Get an instance of the <code>ArrayExport</code> class.
     *
     * @param   string          $target     The file path of the created/overriden
     *                                      php file.
     * @param   array           $data       The array to export.
     * @param   ExportOptions   $options    The export options.
     */
    public function __construct(string $target, array $data = [], ExportOptions $options = null)
    {
        $this->content = '';

        $this->setData($data);
        $this->setTarget($target);

        if (empty($options)) {
            $options = new ExportOptions();
        }

        $this->setOptions($options);
    }

    public function addContent(string $content)
    {
        $this->content .= $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getQuotationMark()
    {
        if (strlen($this->quotationMark) === 1) {
            return $this->quotationMark;
        } elseif (strlen($this->quotationMark) === 2) {
            echo "quot: " . $this->quotationMark . "\n";
            echo "quot 2: " . substr($this->quotationMark, 1) . "\n";
            return substr($this->quotationMark, 1);
        } elseif ($this->quotationMark[0] === '&') {
            return html_entity_decode($this->quotationMark, ENT_QUOTES | ENT_XML1, 'UTF-8');
        } else {
            throw new InvalidArgumentException('Unsupported quotation mark.');
        }
    }

    public function getSanitizer()
    {
        if (!is_object($this->sanitizer)) {
            $this->sanitizer = new ArrayExportSanitizer($this->getQuotationMark());
        }

        return $this->sanitizer;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function export()
    {
        $data = $this->getData();

        $this->setContent('');

        $this->start();
        $this->exportArrayContent($data, 0);
        $this->end();

        if (file_put_contents($this->getTarget(), $this->getContent()) === false) {
            $e = 'Could not write to file :' . $this->getTarget() . '.';

            throw new RuntimeException($e);
        }
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function setOptions(ExportOptions $options)
    {
        $this->options = $options;
    }

    public function setTarget(string $target)
    {
        $this->target = $target;
    }

    protected function end()
    {
        $content = "];" . PHP_EOL;

        $this->addContent($content);
//        return file_put_contents($target, $data, FILE_APPEND);
    }

    protected function exportArrayContent(array $data = [], int $level = 0)
    {
        $level++;

        $sanitizer = $this->getSanitizer();
//        $options = $this->getOptions();
//        $ns = 'extensions.' . FileExtensions::PHP;
//        $indent = $options->get($ns . '.indention');
//        $qm = $options->get($ns . '.quotation_mark');
//        $indentCurrent = $this->getIndentCurrent($indent, $level);
        $isAssoc = array_values($data) != $data;
//        $isAssoc = is_object(json_decode(json_encode($data)));

        foreach ($data as $key => $value) {
            $content = '';

            if ($isAssoc) {
                $content .= $sanitizer->sanitizeLine($value, $level, $key);
            } else {
                $content .= $sanitizer->sanitizeLine($value, $level);
            }

            $this->addContent($content);

            if (is_array($value)) {
                if (!empty($value)) {
                    $this->exportArray($value, $level);
                }

                $this->addContent($sanitizer->closeArray());
            }


//            if ($isAssoc) {
//                $line = $indentCurrent . $qm . $key . $qm . " => ";
//
//                if (is_array($value)) {
//                    $line .= "[";
//                } else {
//                    $line .= $sanitizer->sanitizeValue($value) . "," . PHP_EOL;
////                    $line .= $this->getValueTransfomed($value, $qm) . "," . PHP_EOL;
//                }
//            } else {
//                if (is_array($value)) {
//                    $line = $indentCurrent . "[";
//                } else {
//                    $line = $indentCurrent . $sanitizer->sanitizeValue($value) . "," . PHP_EOL;
////                    $line = $indentCurrent . $this->getValueTransfomed($value, $qm) . "," . PHP_EOL;
//                }
//            }
//
//            file_put_contents($target, $line, FILE_APPEND);
//
//            if (is_array($value)) {
//                if (empty($value)) {
//                    $line = "]," . PHP_EOL;
//                } else {
//                    file_put_contents($target, PHP_EOL, FILE_APPEND);
//
//                    $this->exportArray($target, $value, $indent, $level);
//
//                    $line = $indentCurrent . "]," . PHP_EOL;
//                }
//
//                file_put_contents($target, $line, FILE_APPEND);
//            }
        }
    }

    protected function getIndentCurrent(int $indentNumber, int $level)
    {
        if ($indentNumber === 0 || $level === 0) {
            return '';
        }

        $indent = '';
        $indentReturn = '';

        while ($indentNumber > 0) {
            $indent .= ' ';
            $indentNumber--;
        }

        while ($level > 0) {
            $indentReturn .= $indent;
            $level--;
        }

        return $indentReturn;
    }

    protected function getValueTransfomed($value, string $qm)
    {
        $return = $value;

        if (is_string($value)) {
//            echo "quot getValueTransfomed(): " . $qm . "\n";
            $return = $qm . $value . $qm;
        } elseif (is_bool($value)) {
            $return = $value ? 'true' : 'false';
        }

        return $return;
    }

    protected function start()
    {
        $content = "<?php" . PHP_EOL;

        if ($this->getOptions()->get('extensions.' . FileExtensions::PHP . '.has_extra_line_on_start')) {
            $content .= PHP_EOL;
        }

        $content .= "return [" . PHP_EOL;

        $this->setContent($content);
    }

}
