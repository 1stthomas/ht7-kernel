<?php

namespace Ht7\Kernel\Config\Categories;

use \Ht7\Kernel\Config\Categories\AbstractDotIndexedConfigCategory;
use \Ht7\Kernel\Config\ConfigLoadingSequence;
use \Ht7\Kernel\Config\ConfigPathTypes;
//use \Ht7\Kernel\Config\Models\GenericDotIndexedConfigModel;
use \Ht7\Kernel\Storage\StorageUnit;

/**
 * Container of the config definitions.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class ConfigDefinitionsConfigCategory extends AbstractDotIndexedConfigCategory
{

    /**
     *
     */
    public function cleanUp()
    {
        $locksAll = $this->getLocks();
        $susWithoutKernel = $this->getSUsByConfigPathTypesExcluded([ConfigPathTypes::KERNEL]);
        $susFiltered = [];

        foreach ($susWithoutKernel as $su) {
            $cpt = $su->getStorageModel()->getConfigPathType();
            $ns = 'dirs.' . $cpt . '.remove_locked_entries';

            if ($this->get($ns)) {
                // Avoid saving every change.
                $su->getOut()->setIsSaveImmediately(false);

                $susFiltered[$cpt] = $su;
            }
        }

        foreach ($susFiltered as $key => $su) {
            $dataModel = $su->getDataModel();

            foreach ($locksAll as $cpt => $locks) {
                if ($cpt === $key) {
                    // A file can not restrict the access to its own file.
                    // Later loading files can not do this too.
                    break;
                }

                foreach ($locks as $lock) {
                    if ($dataModel->has($lock)) {
                        $su->getOut()->delete($lock);
                    }
                }
            }

            if ($dataModel->getHasToUpdate()) {
                $su->getOut()->write();

                // Restore the setting.
                $su->getOut()->setIsSaveImmediately(true);
            }
        }
    }

    public function initStorageUnits(array $sus)
    {
        $suKernel = $this->initGetKernelSU($sus);
        $suKernel->getIn()->load();
        $dirsConfig = $suKernel->getDataModel()->get('startup.dirs.default');

        $this->loadingSequence = new ConfigLoadingSequence($dirsConfig);

        // Get an array of the form: [3 => 2, 2 => 1, 1 => 0]
        // where the keys the config path types and their sequence the loading
        // sequence are.
        $this->sus = array_flip(
                array_reverse(
                        $this->loadingSequence->getSequence(),
                        true
                )
        );

        $this->initStorageUnitsAfterKernelInit($sus);

        $this->cleanUp();
    }

    protected function initStorageUnitsAfterKernelInit(array $sus)
    {
        $locks = $this->getLocks();

        /*  @var $su StorageUnit */
        foreach ($sus as $su) {
            if (!is_object($su)) {
                continue;
            }

            $cpt = $su->getStorageModel()->getConfigPathType();

            $this->add($su);

            $this->setupConfigDefinition($su);

            if ($su->getDataModel()->getDefinitions()->has('locks.' . $cpt)) {
                $locks[$cpt] = $su
                        ->getDataModel()
                        ->getDefinitions()
                        ->get('locks.' . $cpt);
            } else {
                $locks[$cpt] = [];
            }
        }

        $this->setLocks($locks);
    }

    protected function initStorageUnitsAdditional()
    {

    }

    protected function mergeConfigDefinitions(StorageUnit $su)
    {
        $defs = [];
        $cpt = $su->getStorageModel()->getConfigPathType();
        $sequence = $this->getLoadingSequence()->getSequenceTo($cpt);

        $i = count($sequence);

        while ($i > 0) {
            $cptTmp = array_pop($sequence);

            $defs = array_replace_recursive($this->getByConfigPathType('dirs.' . $cpt, $cptTmp, []), $defs);

            $i--;
        }

        return $defs;
    }

    protected function setupConfigDefinition(StorageUnit $su)
    {
        $defs = [];
        $cpt = $su->getStorageModel()->getConfigPathType();

        if (in_array($cpt, [ConfigPathTypes::KERNEL, ConfigPathTypes::APP])) {
            $defs = $this->getByConfigPathType('dirs.' . $cpt, $cpt, []);

            array_merge($defs, $su->getDataModel()->get('startup', []));

            if ($cpt === ConfigPathTypes::APP) {
                if (isset($defs['startup']) && isset($defs['startup']['dirs']) && isset($defs['startup']['dirs']['default'])) {
                    unset($defs['startup']['dirs']['default']);
                }

                $defs = array_replace_recursive($this->getByConfigPathType('dirs.' . $cpt, ConfigPathTypes::KERNEL), $defs);
            }
        } else {
            $defs = $this->mergeConfigDefinitions($su);
        }

        if (isset($defs['defaults'])) {
            // Remove the defaults because they belong to the categoriesed configs.
            unset($defs['defaults']);
        }
//        echo "<pre>";
//        print_r($defs);
//        echo "</pre>";

        $su->getDataModel()->setDefinitions($defs);
    }

}
