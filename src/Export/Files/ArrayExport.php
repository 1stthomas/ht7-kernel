<?php

namespace Ht7\Kernel\Export\Files;

use \RuntimeException;
use \Ht7\Kernel\Export\Exportable;

/**
 * Description of Array
 *
 * @author Thomas Pluess
 */
class ArrayExport implements Exportable
{

    protected $indent;
    protected $data;
    protected $quotationMark;
    protected $target;

    /**
     * Get an instance of the <code>ArrayExport</code> class.
     *
     * @param   string  $target     The file path of the created/overriden php file.
     * @param   array   $data       The array to export.
     * @param   int     $indent     The indention per level. The counter starts
     *                              on the second level.
     */
    public function __construct(string $target, array $data = [], int $indent = 4)
    {
        $this->setData($data);
        $this->setIndent($indent);
        $this->setTarget($target);

        $this->setQuotationMark('"');
    }

    public function getData()
    {
        return $this->data;
    }

    public function getIndent()
    {
        return $this->indent;
    }

    public function getQuotationMark()
    {
        return $this->quotationMark;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function export()
    {
        $data = $this->getData();
        $indent = $this->getIndent();
        $target = $this->getTarget();

        if ($this->start($target)) {
            $this->exportArray($target, $data, $indent, 0);

            $this->end($target);
        } else {
            $e = 'Could not write to file :' . $target . '.';

            throw new RuntimeException($e);
        }
    }

    protected function setQuotationMark(string $quotationMark)
    {
        $this->quotationMark = $quotationMark;
    }

    protected function end(string $target)
    {
        $data = "];" . PHP_EOL;

        return file_put_contents($target, $data, FILE_APPEND);
    }

    protected function exportArray(string $target, array $data = [], int $indent = 4, int $level = 0)
    {
        $level++;

        $qm = $this->getQuotationMark();
        $indentCurrent = $this->getIndentCurrent($indent, $level);
        $isAssoc = is_object(json_decode(json_encode($data)));

        foreach ($data as $key => $value) {
            if ($isAssoc) {
                $line = $indentCurrent . $qm . $key . $qm . " => ";

                if (is_array($value)) {
                    $line .= "[";
                } else {
                    $line .= $this->getValueTransfomed($value, $qm) . "," . PHP_EOL;
                }
            } else {
                if (is_array($value)) {
                    $line = $indentCurrent . "[";
                } else {
                    $line = $indentCurrent . $this->getValueTransfomed($value, $qm) . "," . PHP_EOL;
                }
            }

            file_put_contents($target, $line, FILE_APPEND);

            if (is_array($value)) {
                if (empty($value)) {
                    $line = "]," . PHP_EOL;
                } else {
                    file_put_contents($target, PHP_EOL, FILE_APPEND);

                    $this->exportArray($target, $value, $indent, $level);

                    $line = $indentCurrent . "]," . PHP_EOL;
                }

                file_put_contents($target, $line, FILE_APPEND);
            }
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
            $return = $qm . $value . $qm;
        } elseif (is_bool($value)) {
            $return = $value ? 'true' : 'false';
        }

        return $return;
    }

    protected function setData(array $data)
    {
        $this->data = $data;
    }

    protected function setIndent(int $indent)
    {
        $this->indent = $indent;
    }

    protected function setTarget(string $target)
    {
        $this->target = $target;
    }

    protected function start(string $target)
    {
        $data = "<?php" . PHP_EOL;
        $data .= "return [" . PHP_EOL;

        return file_put_contents($target, $data);
    }

}
