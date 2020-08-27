<?php

namespace Ht7\Kernel\Tasks;

use \RuntimeException;
use \Ht7\Kernel\Container;
use \Ht7\Kernel\KernelStatus;
use \Ht7\Kernel\Tasks\AbstractTask;

/**
 * Description of FindAppConfigTask
 *
 * @author Thomas Pluess
 */
class AnalyseFolderStructureTask extends AbstractTask
{

    public function __construct(string $type, Container $container)
    {
        parent::__construct(KernelStatus::ANALYSE_FOLDER_STRUCTURE, $type, $container);

        $this->description = 'Analyse the basic folder structure.';

        $this->creates = [
            'paths.cms',
            'paths.app',
            'paths.config_cms',
            'paths.config_app',
        ];
        $this->needs = [
            'paths.index',
            'paths.dispatcher',
        ];
    }

    public function process()
    {
        $container = $this->getContainer();
        $pathIndex = $container->get('paths.index');
        $pathDispatcher = $container->get('paths.dispatcher');

        $appAdd = DIRECTORY_SEPARATOR . 'app';
        $cmsAdd = DIRECTORY_SEPARATOR . 'cms';
        $configAdd = DIRECTORY_SEPARATOR . 'config';

        if ($pathIndex === $pathDispatcher) {
            $str = 'The dispatcher file must be in another folder than the calling index file.';
            throw new RuntimeException($str);
        } else {
            if (substr($pathDispatcher, -3, 3) === 'cms') {
                $pathCms = $pathDispatcher;
                $pathApp = rtrim($pathDispatcher, DIRECTORY_SEPARATOR . 'cms') . $appAdd;
            } else {
                $pathCms = $pathDispatcher . $cmsAdd;
                $pathApp = $pathDispatcher . $appAdd;
            }
        }

        $pathCmsConfig = $pathCms . $configAdd;
        $pathAppConfig = $pathApp . $configAdd;

        $container->addPlain('paths.cms', $pathCms);
        $container->addPlain('paths.app', $pathApp);
        $container->addPlain('paths.config_cms', $pathCmsConfig);
        $container->addPlain('paths.config_app', $pathAppConfig);

        return $this->getName();
    }

}
