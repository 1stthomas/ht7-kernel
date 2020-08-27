<?php

namespace Ht7\Kernel\Exceptions;

use \Exception;
use \Psr\Container\ContainerExceptionInterface;

/**
 * Description of NotFoundException
 *
 * @author Thomas Pluess
 */
class ContainerResolvingException extends Exception implements ContainerExceptionInterface
{

}
