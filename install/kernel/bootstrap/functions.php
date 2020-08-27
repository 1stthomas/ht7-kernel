<?php

use \Ht7\Kernel\Container as KernelContainer;

/**
 * Get the CMS container instance.
 *
 * @return  mixed
 */
function getC()
{
    return KernelContainer::getInstance()->get('instances.cms_container');
}

function printLine($str)
{
    echo "<p>" . $str . "</p>";
}

function printStructure($structure)
{
    echo "<pre>";
    print_r($structure);
    echo "</pre>";
}

function printTitle(string $str, int $size = 3)
{
    echo "<h" . $size . ">" . $str . "</h" . $size . ">";
}
