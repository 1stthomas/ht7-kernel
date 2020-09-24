<?php

namespace Ht7\Kernel\Models;

use \Ht7\Kernel\Models\ArrayIndexedModel;
use \Ht7\Kernel\Models\ArrayDotIndexable;

/**
 * Abstract implementation of the <code>ArrayDottedIndexable</code> interface.
 *
 * An implementation of this class can set, get or has requests of an element by
 * using following syntax:
 *
 * <code>
 * $implementation = new Implementation();<br /><br />
 * // Check if an element is present:<br />
 * if ($implementation->has('dir.kernel.config')) [<br />
 * &nbsp;&nbsp;&nbsp;&nbsp;// retrieve an element with the index 'dir.kernel.config':<br />
 * &nbsp;&nbsp;&nbsp;&nbsp;$element = $implementation->get('dir.kernel.config');<br />
 * }<br />
 * </code>
 *
 * @author Thomas Pluess
 */
class ArrayDotIndexedModel extends ArrayIndexedModel implements ArrayDotIndexable
{

    /**
     * {@inheritdoc}
     */
    public function get(string $index)
    {
        $parts = explode('.', $index);
        $values = $this->getAll();

        switch (count($parts)) {
            case 1:
                return $values[$parts[0]];
            case 2:
                return $values[$parts[0]][$parts[1]];
            case 3:
                return $values[$parts[0]][$parts[1]][$parts[2]];
            case 4:
                return $values[$parts[0]][$parts[1]][$parts[2]][$parts[3]];
            case 5:
                return $values[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]];
            case 6:
                return $values[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]];
            case 7:
                return $values[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]][$parts[6]];
            case 8:
                return $values[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]][$parts[6]][$parts[7]];
            case 9:
                return $values[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]][$parts[6]][$parts[7]][$parts[8]];
            case 10:
                return $values[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]][$parts[6]][$parts[7]][$parts[8]][$parts[9]];
            case 0:
            default:
                return $values[$index];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $index)
    {
        $parts = explode('.', $index);
        $values = $this->getAll();
        $count = count($parts);
        $i = 0;
        $has = true;

        if ($count === 0) {
            $has = isset($values[$index]);
        } else {
            while ($i < $count) {
                if (isset($values[$parts[$i]])) {
                    $values = $values[$parts[$i]];
                } else {
                    $has = false;
                }

                $i++;
            }
        }

        return $has;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $index, $value)
    {
        $parts = explode('.', $index);

        switch (count($parts)) {
            case 1:
                $this->items[$parts[0]] = $value;
                break;
            case 2:
                $this->items[$parts[0]][$parts[1]] = $value;
                break;
            case 3:
                $this->items[$parts[0]][$parts[1]][$parts[2]] = $value;
                break;
            case 4:
                $this->items[$parts[0]][$parts[1]][$parts[2]][$parts[3]] = $value;
                break;
            case 5:
                $this->items[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]] = $value;
                break;
            case 6:
                $this->items[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]] = $value;
                break;
            case 7:
                $this->items[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]][$parts[6]] = $value;
                break;
            case 8:
                $this->items[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]][$parts[6]][$parts[7]] = $value;
                break;
            case 9:
                $this->items[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]][$parts[6]][$parts[7]][$parts[8]] = $value;
                break;
            case 10:
                $this->items[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]][$parts[6]][$parts[7]][$parts[8]][$parts[9]] = $value;
                break;
            case 0:
            default:
                $this->items[$index] = $value;
                break;
        }

        $this->setHasToUpdate(true);
    }

}
