<?php

namespace Ht7\Kernel\Tasks;

use \Ht7\Kernel\Container;
use \Ht7\Kernel\KernelStatus;
use \Ht7\Kernel\Tasks\AbstractTask;
use \Patchwork\Utf8\Bootup;

/**
 * Description of FindAppConfigTask
 *
 * @author Thomas Pluess
 */
class FixPhpEnvironmentTask extends AbstractTask
{

    public function __construct(string $type, Container $container)
    {
        parent::__construct(KernelStatus::FIX_PHP_ENVIRONMENT, $type, $container);

        $this->description = 'Fix the PHP environment by preparing proper utf-8'
                . 'encoding.';

        $this->creates = [];
        $this->needs = [];
    }

    public function process()
    {
        // Enables the portablity layer and configures PHP for UTF-8.
        Bootup::initAll();
        // Redirects to an UTF-8 encoded URL if it's not already the case.
        Bootup::filterRequestUri();
        // Normalizes HTTP inputs to UTF-8 NFC
        Bootup::filterRequestInputs();

        return $this->getName();
    }

}
