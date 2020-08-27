<?php

namespace Ht7\Kernel\Config\Models;

use \Ht7\Kernel\Config\Models\AbstractConfig;

/**
 * Description of AbstractConfig
 *
 * @author Thomas Pluess
 */
class AbstractDottedConfig extends AbstractConfig
{

    public function __construct(int $configPathType, string $filePath = '')
    {
        parent::__construct($configPathType, $filePath);
    }

    public function get($index)
    {
        $parts = explode('.', $index);
        $values = $this->getValues();

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

    public function has($index)
    {
        $parts = explode('.', $index);
        $values = $this->getValues();
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

//        ob_start();
//        $this->get($index);
//        $out = ob_get_clean();
//
//        return empty($out);
//        try {
//            $this->get($index);
//        } catch (Exception $e) { // Can not catch a notice. Would have to make an own ErrorHandler
//            // see
//            return false;
//        }
//
//        return true;
//        $parts = explode('.', $index);
//        $values = $this->getValues();
//
//        switch (count($parts)) {
//            case 1:
//                return isset($values[$parts[0]]);
//            case 2:
//                return isset($values[$parts[0]][$parts[1]]);
//            case 3:
//                return isset($values[$parts[0]][$parts[1]][$parts[2]]);
//            case 4:
//                return isset($values[$parts[0]][$parts[1]][$parts[2]][$parts[3]]);
//            case 5:
//                return isset($values[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]]);
//            case 6:
//                return isset($values[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]]);
//            case 7:
//                return isset($values[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]][$parts[6]]);
//            case 8:
//                return isset($values[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]][$parts[6]][$parts[7]]);
//            case 9:
//                return isset($values[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]][$parts[6]][$parts[7]][$parts[8]]);
//            case 10:
//                return isset($values[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]][$parts[6]][$parts[7]][$parts[8]][$parts[9]]);
//            case 0:
//            default:
//                return isset($values[$index]);
//        }
    }

    public function set(string $index, $value)
    {
        $parts = explode('.', $index);

        switch (count($parts)) {
            case 1:
                $this->values[$parts[0]] = $value;
                break;
            case 2:
                $this->values[$parts[0]][$parts[1]] = $value;
                break;
            case 3:
                $this->values[$parts[0]][$parts[1]][$parts[2]] = $value;
                break;
            case 4:
                $this->values[$parts[0]][$parts[1]][$parts[2]][$parts[3]] = $value;
                break;
            case 5:
                $this->values[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]] = $value;
                break;
            case 6:
                $this->values[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]] = $value;
                break;
            case 7:
                $this->values[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]][$parts[6]] = $value;
                break;
            case 8:
                $this->values[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]][$parts[6]][$parts[7]] = $value;
                break;
            case 9:
                $this->values[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]][$parts[6]][$parts[7]][$parts[8]] = $value;
                break;
            case 10:
                $this->values[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]][$parts[6]][$parts[7]][$parts[8]][$parts[9]] = $value;
                break;
            case 0:
            default:
                $this->values[$index] = $value;
                break;
        }
    }

}
