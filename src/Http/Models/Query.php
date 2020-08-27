<?php

namespace Ht7\Kernel\Http\Models;

use \Ht7\Base\Lists\ItemList;

/**
 * Description of Query
 *
 * @author Thomas Pluess
 */
class Query extends ItemList
{

    public function __construct(array $request)
    {
        parent::__construct($request);
    }

    public function add($item)
    {
        $this->items[$item[0]] = $item[1];
    }

    public function load($request)
    {
        foreach ($request as $key => $value) {
            $this->add([$key, $value]);
        }
    }

}
