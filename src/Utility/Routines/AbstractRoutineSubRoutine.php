<?php

namespace Ht7\Kernel\Utility\Routines;

use \Ht7\Kernel\Utility\Routines\AbstractRoutine;
use \Ht7\Kernel\Utility\Routines\RoutineSubRoutinable;

/**
 * Description of AbstractRoutine
 *
 * @author Thomas Pluess
 */
abstract class AbstractRoutineSubRoutine extends AbstractRoutine implements RoutineSubRoutinable
{

    protected $subRoutines;

    public function __construct(string $name, array $subRoutines = [], array $args = [])
    {
        parent::__construct($name, $args);

        $this->setSubRoutines($subRoutines);
    }

    public function exclude(array $excludes)
    {
        $subRoutines = $this->getSubRoutines();
        $subsNew = array_diff($subRoutines, $excludes);

        $this->setSubRoutines($subsNew);
    }

    public function getSubRoutines()
    {
        return $this->subRoutines;
    }

    public function provide(array $provides)
    {
        $subRoutines = $this->getSubRoutines();
        $subsNew = array_filter(
                $subRoutines,
                function($class) use ($provides) {
            return in_array($class, $provides);
        }
        );

        $this->setSubRoutines($subsNew);
    }

    public function runSubRoutines(array $args = [])
    {
        foreach ($this->getSubRoutines() as $class) {
            $sr = new $class($args);
            $sr->run();
        }
    }

    public function setSubRoutines(array $subRoutines)
    {
        $this->subRoutines = $subRoutines;
    }

}
