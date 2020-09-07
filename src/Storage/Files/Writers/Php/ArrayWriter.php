<?php

namespace Ht7\Kernel\Storage\Files\Writers\Php;

use \RuntimeException;
use \Ht7\Kernel\Export\Exportable;
use \Ht7\Kernel\Export\Files\ExportOptions;
use \Ht7\Kernel\Storage\Files\Writers\Php\Transformers\ArrayToStringable;

/**
 * The <code>ArrayWriter</code> class can write a PHP array to a php file. Existing
 * files will be overridden.
 *
 * Example:
 *
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class ArrayWriter implements Exportable
{

    /**
     *
     * @var     array               The data to write. Typically a multidimensional
     *                              assoc array.
     */
    protected $data;

    /**
     *
     * @var     ArrayToStringable   This object transforms the array into
     *                              a string which can be used to save the origin
     *                              array into a PHP file.
     */
    protected $transformer;

    /**
     *
     * @var     string              The file path where the data will be written
     *                              to.
     */
    protected $target;

    /**
     * Get an instance of the <code>ArrayWriter</code> class.
     *
     * @param   string          $target         The file path of the created/overriden
     *                                          php file.
     * @param   ExportOptions   $transformer    The object which transforms the array
     *                                          into a PHP string..
     * @param   array           $data           The array to export.
     */
    public function __construct(string $target, ArrayToStringable $transformer, array $data = [])
    {
        $this->setData($data);
        $this->setTarget($target);
        $this->setTransformer($transformer);
    }

    /**
     * Write the defined array into its string representation.
     *
     * @return  null|string                 In case the target was not defined,
     *                                      return the composed string. Otherwise
     *                                      write it into the defined file.
     * @throws  RuntimeException
     */
    public function export()
    {
        // Get the array to transform.
        $data = $this->getData();

        $content = '';

        // Compose the array string.
        $content .= $this->start();
        $content .= $this->exportArrayContent($data, 0);
        $content .= $this->end();

        if (empty($this->getTarget())) {
            // No target has been specified, return the composed string.
            return $this->getContent();
        } elseif (file_put_contents($this->getTarget(), $content) === false) {
            $e = 'Could not write to file :' . $this->getTarget() . '.';

            throw new RuntimeException($e);
        }
    }

    /**
     * Get the original array of this export.
     *
     * @return  array                   The input array.
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get the file path where the array should be written.
     *
     * @return  string                  The path to the file to write to.
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Get the array to string transformer.
     *
     * @return  ArrayToStringable       The array to string transformer.
     */
    public function getTransformer()
    {
        return $this->transformer;
    }

    /**
     * Set the array to write into a PHP file.
     *
     * @param   array   $data           The array to write into a file. This can
     *                                  be indexed or assoc.
     * @return  void
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * Set the file path to where the array should be written.
     *
     * @param   string  $target     The path to the file to write to.
     */
    public function setTarget(string $target)
    {
        $this->target = $target;
    }

    /**
     * Set the instance which transforms the PHP array into its string representation
     * to write into a PHP file.
     *
     * @param ArrayToStringable $arrayToString
     */
    public function setTransformer(ArrayToStringable $arrayToString)
    {
        $this->transformer = $arrayToString;
    }

    /**
     * Create the file end string.
     *
     * @return  string                  The string of the file end.
     */
    protected function end()
    {
        return $this->getTransformer()->createFileEnd();
    }

    /**
     * Call recursively the ArrayToString object, to transform multidimensional
     * arrays into its string representation.
     *
     * @param   array   $data           The array data to transform.
     * @param   int     $level          The current level of the array.
     * @return  string                  The string representation of the current
     *                                  array level.
     */
    protected function exportArrayContent(array $data = [], int $level = 0)
    {
        $level++;

        $content = '';
        $transformer = $this->getTransformer();
        $isAssoc = array_values($data) != $data;

        foreach ($data as $key => $value) {
//            $content = '';

            if ($isAssoc) {
                $content .= $transformer->createLine($value, $level, $key);
            } else {
                $content .= $transformer->createLine($value, $level);
            }

//            $this->addContent($content);

            if (is_array($value)) {
                if (!empty($value)) {
                    $content .= $this->exportArrayContent($value, $level);
                }

                $content .= $transformer->createArrayEnd($level, !empty($value));
//                $this->addContent($sanitizer->createArrayEnd($level, !empty($value)));
            }
        }

        return $content;
    }

    /**
     * Create the file start string.
     *
     * @return  string                  The file start string.
     */
    protected function start()
    {
        return $this->getTransformer()->createFileStart();
    }

}
