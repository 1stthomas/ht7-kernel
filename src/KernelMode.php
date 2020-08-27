<?php

namespace Ht7\Kernel;

use \Ht7\Base\Enum;

/**
 * Description of RowTypes
 *
 * @author Thomas Pluess
 */
class KernelMode extends Enum
{

    /**
     * Specify the current row as a body row.
     */
    const DEBUG = 1;
    const DEVELOP = 2;
    const PRODUCTION = 3;

}
