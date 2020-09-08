<?php

namespace Ht7\Kernel\Storage\Files;

use \Ht7\Base\Enum;

/**
 * Specification of the supported file extensions.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class FileExtensions extends Enum
{

    /**
     * The file looks like: <code>file.php</code>.
     */
    const PHP = 'php';

    /**
     * The file looks like: <code>file.json</code>.
     */
    const JSON = 'json';

}
