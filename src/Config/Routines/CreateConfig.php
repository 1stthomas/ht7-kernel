<?php

namespace Ht7\Kernel\Config\Routines;

use \Ht7\Kernel\Utility\Routines\AbstractRoutineSubRoutine;
use \Ht7\Kernel\Config\Routines\SubRoutines\CreateConfigDefinitions;

/**
 * Description of CreateConfig
 *
 * @author Thomas Pluess
 */
class CreateConfig extends AbstractRoutineSubRoutine
{

    public function __construct(array $excludes = [], array $provides = [])
    {
        parent::__construct(
                'create_the_config',
                [
                    CreateConfigDefinitions::class,
//                    CreateDefaultConfigDefinitions::class,
                ],
                []
        );

        $this->exclude($excludes);
        $this->provide($provides);
    }

    public function run()
    {
        $this->runSubRoutines($this->getArgs());
    }

}
