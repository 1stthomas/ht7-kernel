<?php

namespace Ht7\Kernel\Tasks;

use \Ht7\Kernel\Container;

/**
 * Description of AbstractTask
 *
 * @author Thomas Pluess
 */
abstract class AbstractTask
{

    protected $container;
    protected $creates;
    protected $deletes;
    protected $description;
    protected $name;
    protected $needs;
    protected $type;

    public function __construct(string $name, string $type, Container $container)
    {
        $this->description = '';
        $this->creates = [];
        $this->deletes = [];
        $this->needs = [];

        $this->setContainer($container);
        $this->setName($name);
        $this->setType($type);
    }

    /**
     *
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    public function getCreates()
    {
        return $this->creates;
    }

    public function getDeletes()
    {
        return $this->deletes;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getNeeds()
    {
        return $this->needs;
    }

    public function getType()
    {
        return $this->type;
    }

    abstract public function process();

    protected function setContainer(Container $container)
    {
        $this->container = $container;
    }

    protected function setDescription(string $description)
    {
        $this->description = $description;
    }

    protected function setName(string $name)
    {
        $this->name = $name;
    }

    protected function setType(string $type)
    {
        $this->type = $type;
    }

}
