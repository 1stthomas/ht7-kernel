<?php

namespace Ht7\Kernel\Config\Categories;

use \RuntimeException;
use \Ht7\Kernel\Config\Categories\AbstractDotIndexedConfigCategory;
use \Ht7\Kernel\Config\ConfigLoadingSequence;
use \Ht7\Kernel\Config\ConfigPathTypes;
use \Ht7\Kernel\Config\Storage\ConfigDefinitionsStorageUnit;
use \Ht7\Kernel\Config\Storage\StorageUnitList;

/**
 * Container of the config definitions.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class ConfigDefinitionsCategory extends AbstractDotIndexedConfigCategory
{

    /**
     * A storage unit list with only dummy storage units. These units are placed
     * in the reverse loading sequence.
     *
     * @var     StorageUnitList
     */
    protected $susDummy;

    /**
     * Get a clone of the dummy list.
     *
     * This list contains a dummy config storage unit of each defined config
     * storage location in the reversed order of the loading sequence.
     *
     * @return  StorageUnitList
     */
    public function getStorageUnitListDummy()
    {
        if ($this->susDummy === null) {
            $this->susDummy = new StorageUnitList($this->getLoadingSequence());
        }

        return (clone $this->susDummy);
    }

    /**
     * Initialize the config by following tasks:
     * - initialize the loading sequence.
     * - initialize the storage unit list.
     * - replace/remove locked config items.
     *
     * @param   array   $sus            Indexed array of <code>ConfigDefinitionsStorageUnit</code>
     *                                  instances.
     * @return  void
     */
    public function initStorageUnits(array $sus)
    {
        $this->initLoadingSequence($sus);

        $this->initStorageUnitList($sus);

        $this->fixLocksRelated();
    }

    /**
     * Add multiple storage units to the storage unit list.
     *
     * @param   array   $sus            Indexed array of <code>ConfigDefinitionsStorageUnit</code>
     *                                  instances.
     */
    protected function addStorageUnits(array $sus)
    {
        /*  @var $su ConfigDefinitionsStorageUnit */
        foreach ($sus as $su) {
            $this->setupConfigAdditionals($su);

            $this->setupLocks($su);

            $this->cleanUpConfigDefinitionDefinitions($su);

            $this->getStorageUnitList()->add($su);
        }
    }

    protected function cleanUpConfigDefinitionDefinitions(ConfigDefinitionsStorageUnit $su)
    {
        $all = $su->getDataModel()->getDefinitions()->getAll();

        if (isset($all['defaults'])) {
            unset($all['defaults']);
        }
        if (isset($all['dirs'])) {
            unset($all['dirs']);
        }
        if (isset($all['locks'])) {
            unset($all['locks']);
        }

        $su->getDataModel()->getDefinitions()->setAll($all);
    }

    protected function fixLocksRelated()
    {
        $susWithoutKernel = $this->getStorageUnitList()->getByConfigPathTypesExcluded([ConfigPathTypes::KERNEL]);

        /* @var $su ConfigDefinitionsStorageUnit */
        foreach ($susWithoutKernel as $su) {
            $cpt = $su->getStorageModel()->getConfigPathType();
            $ns = 'definitions.general.remove_locked_entries';

            if ($this->getStorageUnitList()->getByConfigPathTypeMax($ns, $cpt)) {
                // Avoid saving every change.
                $su->getOut()->setIsSaveImmediately(false);

                $this->cleanupLockedItems($su);
                $this->cleanupLocks($su);

                if ($su->getDataModel()->getHasToUpdate()) {
                    $su->getOut()->write();

                    // Restore the setting.
                    $su->getOut()->setIsSaveImmediately(true);
                }
            }
        }
    }

    protected function cleanupLockedItems(ConfigDefinitionsStorageUnit $su)
    {
        $cpt = $su->getStorageModel()->getConfigPathType();
        $dataModel = $su->getDataModel();
        $unreplaceables = $dataModel->getDefinitions()->get('unreplaceables', []);

        foreach ($this->getLocks() as $cptLocks => $locks) {
            if ($cptLocks === $cpt) {
                // A file can not restrict the access to its own file.
                // Later loading files can not do this too.
                break;
            }

            foreach ($locks as $lock) {
                if (substr($lock, 0, 9) === 'defaults.' && !in_array($lock, $unreplaceables)) {
                    $dataModel
                            ->getDefinitions()
                            ->set(
                                    substr($lock, 9),
                                    $this->getStorageUnitList()->getByConfigPathTypeMax($lock, $cptLocks)
                    );
                }
                if (substr($lock, 0, 12) === 'definitions.' && !in_array($lock, $unreplaceables)) {
                    $dataModel
                            ->getDefinitions()
                            ->set(
                                    substr($lock, 12),
                                    $this->getStorageUnitList()->getByConfigPathTypeMax($lock, $cptLocks)
                    );
                }
                if ($dataModel->has($lock)) {
                    $su->getOut()->delete($lock);
                }
            }
        }
    }

    protected function cleanupLocks(ConfigDefinitionsStorageUnit $su)
    {
        $locks = $this->getLocks()->get($su->getStorageModel()->getConfigPathType())->getAll();

        if (count($su->getDataModel()->get('definitions.locks')) !== count($locks)) {
            $su->getDataModel()->set('definitions.locks', array_values($locks));
        }
    }

    protected function fixUnmergeables(ConfigDefinitionsStorageUnit $su)
    {
        if ($su->getStorageModel()->getConfigPathType() === ConfigPathTypes::KERNEL) {
            return;
        }

        $unmergeables = $su->getDataModel()->getDefinitions()->get('unmergeables');

        foreach ($unmergeables as $unmergeable) {
            if ($su->getDataModel()->has($unmergeable)) {
                $su->getDataModel()
                        ->getDefinitions()
                        ->set(substr($unmergeable, 12), $su->getDataModel()->get($unmergeable));
            }
        }
    }

    /**
     * Compose the loading sequence of the config folders.
     *
     * This method looks only in the <code>config_definitions.php</code> of the
     * kernel and app configs. Additional paths have to be added to the
     * <code>config_definitions.php</code> of the app path.
     *
     * @param   array   $sus            The storage units added on the instance
     *                                  construct.
     * @throws  RuntimeException
     */
    protected function initLoadingSequence(array $sus)
    {
        $susFiltered = [];

        foreach ($sus as $su) {
            if ($su->getStorageModel()->getConfigPathType() === ConfigPathTypes::KERNEL) {
                $susFiltered[0] = $su;
            } elseif ($su->getStorageModel()->getConfigPathType() === ConfigPathTypes::APP) {
                $susFiltered[1] = $su;
            } elseif ($su->getStorageModel()->getConfigPathType() === ConfigPathTypes::OVERRIDE) {
                $susFiltered[2] = $su;
            } else {
                continue;
            }

            $su->getIn()->load();
        }

        if (!is_object($susFiltered[0])) {
            $e = 'Missing storage unit of the kernel.';

            throw new RuntimeException($e);
        } elseif (!is_object($susFiltered[1])) {
            $e = 'Missing storage unit of the app.';

            throw new RuntimeException($e);
        }

        if (isset($susFiltered[2]) && $susFiltered[2]->getDataModel()->has('definitions.dirs.additional')) {
            $additionals = $susFiltered[2]->getDataModel()->get('definitions.dirs.additional');
        } elseif ($susFiltered[1]->getDataModel()->has('definitions.dirs.additional')) {
            $additionals = $susFiltered[1]->getDataModel()->get('definitions.dirs.additional');
        } else {
            $additionals = $susFiltered[0]->getDataModel()->get('definitions.dirs.additional');
        }

        $this->loadingSequence = new ConfigLoadingSequence(
                $susFiltered[0]->getDataModel()->get('definitions.dirs.default'),
                $additionals
        );
    }

    protected function initStorageUnitList(array $sus)
    {

        $this->sus = new StorageUnitList($this->getLoadingSequence());
        $this->susDummy = clone $this->sus;

        $this->addStorageUnits($sus);
    }

    protected function initStorageUnitsAdditional()
    {

    }

    protected function mergeConfigDefaults(ConfigDefinitionsStorageUnit $su)
    {
        $cpt = $su->getStorageModel()->getConfigPathType();

        if ($su->getDataModel()->has('defaults')) {
            $defs = $su->getDataModel()->get('defaults', []);
        } else {
            $defs = [];
        }

        if ($cpt !== ConfigPathTypes::KERNEL) {
            $defs = array_replace_recursive(
                    $this->getStorageUnitList()
                            ->getPrevious($cpt)
                            ->getDataModel()
                            ->getDefaults()
                            ->getAll(),
                    $defs
            );
        }

        return $defs;
    }

    protected function mergeConfigDefinitions(ConfigDefinitionsStorageUnit $su)
    {
        $cpt = $su->getStorageModel()->getConfigPathType();

        if ($su->getDataModel()->has('definitions')) {
            $defs = $su->getDataModel()->get('definitions', []);
        } else {
            $defs = [];
        }

        if ($cpt !== ConfigPathTypes::KERNEL) {
            $defs = array_replace_recursive(
                    $this->getStorageUnitList()
                            ->getPrevious($cpt)
                            ->getDataModel()
                            ->getDefinitions()
                            ->getAll(),
                    $defs
            );
        }

        return $defs;
    }

    protected function setupConfigAdditionals(ConfigDefinitionsStorageUnit $su)
    {
        $su->getDataModel()
                ->setDefaults($this->mergeConfigDefaults($su));

        $su->getDataModel()
                ->setDefinitions($this->mergeConfigDefinitions($su));

        $this->fixUnmergeables($su);
    }

    protected function setupLocks(ConfigDefinitionsStorageUnit $su)
    {
        if ($su->getDataModel()->getDefinitions()->has('locks')) {
            $locks = $su
                    ->getDataModel()
                    ->getDefinitions()
                    ->get('locks');
        } else {
            $locks = [];
        }

        $this->getLocks()->addMultiple([
            $su->getStorageModel()->getConfigPathType() => $locks
        ]);
    }

}
