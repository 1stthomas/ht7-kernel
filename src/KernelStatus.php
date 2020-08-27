<?php

namespace Ht7\Kernel;

use \Ht7\Base\Enum;

/**
 * Description of RowTypes
 *
 * @author Thomas Pluess
 */
class KernelStatus extends Enum
{

    /**
     * Specify the current row as a body row.
     */
    const INITIALISED = 'initialised';
    const INSTALL_KERNEL = 'install_the_kernel';
    const RUN_KERNEL_TASKS = 'run_the_kernel_tasks';
    const ANALYSE_ENVIRONMENT = 'analyse_environment';
    const FIX_PHP_ENVIRONMENT = 'fix_php_environment';
    const ANALYSE_FOLDER_STRUCTURE = 'analyse_folder_structure';
    const READ_CONFIG = 'read_cms_and_app_config';
    const CREATE_CMS_CONTAINER = 'create_cms_container';
    const SETUP_KERNEL_TASKLIST = 'setup_kernel_tasklist';
    const LOAD_FUNCTIONS = 'load_functions';
    const LOAD_SINGLETONS = 'load_singletons';

//    const START_SESSION = 'start_session';
//    const VALIDATE_REQUEST = 'validate_request';
}
