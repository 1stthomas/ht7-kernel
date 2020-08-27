<?php

namespace Ht7\Kernel\Utility\Routines;

use \Ht7\Kernel\Utility\Routines\Routinable;

/**
 *
 * @author Thomas Pluess
 */
interface RoutineSubRoutinable extends Routinable
{

    public function exclude(array $excludes);

    public function provide(array $provides);

    public function runSubRoutines(array $args = []);
}
