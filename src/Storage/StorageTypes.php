<?php

namespace Ht7\Kernel\Storage;

use \Ht7\Base\Enum;

/**
 * Specification of the storage types.
 *
 * Beaware that cookies do not work on CLI requests.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class StorageTypes extends Enum
{

    /**
     * The data is stored in files on the server.
     */
    const FILE = 1;

    /**
     * The data is stored in the db.
     */
    const DB = 2;

    /**
     * The data is stored in the client session.
     *
     * In case of <code>session.options_php.use_cookies = 1</code> by a CLI
     * request, this storage will not work.
     */
    const SESSION = 3;

    /**
     * The data is stored in a cookie on the client.
     *
     * This does not work in case the request is from a CLI.
     */
    const COOKIE = 4;

}
