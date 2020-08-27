<?php

namespace Ht7\Kernel\Kernel;

use \Ht7\Base\Enum;

/**
 * Description of RowTypes
 *
 * @author Thomas Pluess
 */
class RequestMode extends Enum
{

    /**
     * Specify the current row as a body row.
     */
    const HTTP = 1;
    const CLI = 2;

}
