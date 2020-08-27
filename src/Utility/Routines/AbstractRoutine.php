<?php

namespace Ht7\Kernel\Utility\Routines;

use \Ht7\Kernel\Utility\Routines\Routinable;

/**
 * Description of AbstractRoutine
 *
 * @author Thomas Pluess
 */
abstract class AbstractRoutine implements Routinable
{

    protected $args;

    /**
     * @var string          The routine name.
     */
    protected $name;
    protected $response;

    public function __construct(string $name, array $args = [])
    {
        $this->setArgs($args);
        $this->setName($name);
    }

    public function getArgs()
    {
        return $this->args;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getResponse()
    {
        return $this->response;
    }

    abstract public function run();

    public function setArgs(array $args)
    {
        $this->args = $args;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setResponse(string $response)
    {
        $this->response = $response;
    }

}
