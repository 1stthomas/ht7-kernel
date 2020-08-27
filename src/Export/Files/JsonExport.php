<?php

namespace Ht7\Kernel\Export\Files;

use \RuntimeException;
use \Ht7\Kernel\Export\Exportable;

/**
 * Description of Array
 *
 * @author Thomas Pluess
 */
class JsonExport implements Exportable
{

    protected $data;
    protected $flags;
    protected $target;

    public function __construct(string $target, array $data = [], int $flags = 0)
    {
        $this->setData($data);
        $this->setFlags($flags);
        $this->setTarget($target);
    }

    public function getData()
    {
        return $this->data;
    }

    public function getFlags()
    {
        return $this->flags;
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
        $flags = $this->getFlags();
        $target = $this->getTarget();

        $json = json_encode($data, $flags);

        $response = file_put_contents($target, $json);

        if ($response) {
            //
        } else {
            $e = 'Could not write to file :' . $target . '.';

            throw new RuntimeException($e);
        }
    }

    protected function setData(array $data)
    {
        $this->data = $data;
    }

    protected function setFlags(int $flags)
    {
        $this->flags = $flags;
    }

    protected function setTarget(string $target)
    {
        $this->target = $target;
    }

}
