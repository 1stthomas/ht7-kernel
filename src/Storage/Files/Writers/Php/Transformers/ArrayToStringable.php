<?php

namespace Ht7\Kernel\Storage\Files\Writers\Php\Transformers;

/**
 * This interface describes the necessary methods for a class, which can transform
 * a PHP array into its string representation.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
interface ArrayToStringable
{

    /**
     * Create the end string of an array.
     *
     * @param   int     $level          The current array level.
     * @param   bool    $addIndent      True if the indent of the current level
     *                                  should be added.
     * @return  string                  The closing of an array and a new Line.
     */
    public function createArrayEnd(int $level, bool $addIndent = true);

    /**
     * Create the end string of a PHP file with the last closing array.
     *
     * @return  string                  The closing of the last array and the
     *                                  file end.
     */
    public function createFileEnd();

    /**
     * Create the file start.
     *
     * @return  string                  The file start with the definition of the
     *                                  first array.
     */
    public function createFileStart();

    /**
     * Create a single line of an array to string transformation.
     *
     * @param   mixed       $value      The current array value to transform.
     * @param   int         $level      The current code level.
     * @param   string|int|null     $key    The current key. An indexed array
     *                                  will not have a key.
     * @return  string                  A single line of the array to string
     *                                  transformation.
     */
    public function createLine($value, int $level, $key = null);
}
