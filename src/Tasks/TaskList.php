<?php

namespace Ht7\Kernel\Tasks;

use \Ht7\Base\Exceptions\InvalidDatatypeException;
use \Ht7\Base\Lists\ItemList;
use \Ht7\Kernel\Tasks\AbstractTask;

/**
 * Description of TaskList
 *
 * @author Thomas Pluess
 */
class TaskList extends ItemList
{

    public function add($item)
    {
        if ($item instanceof AbstractTask) {
            parent::add($item);
        } else {
            parent::add((new $item()));
//            throw new InvalidDatatypeException('The item', $item, [], [AbstractTask::class]);
        }
    }

}
