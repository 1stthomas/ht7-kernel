<?php

namespace Ht7\CmsSimple\Kernel\Routines;

use \Ht7\CmsSimple\Utility\Routines\AbstractRoutineSubRoutine;
use \Ht7\CmsSimple\Kernel\Routines\SubRoutines\RebuildConfigCache;

/**
 * Description of RebuildCache
 *
 * @author Thomas Pluess
 */
class RebuildCache extends AbstractRoutineSubRoutine
{

    public function __construct(array $excludes = [], array $provides = [])
    {
        parent::__construct(
                'rebuild_cache_complete',
                [
                    RebuildConfigCache::class
                ],
                []
        );

        $this->exclude($excludes);
        $this->provide($provides);
    }

    public function run()
    {
        $this->runSubRoutines();
    }

}
