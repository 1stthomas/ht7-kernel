<?php

namespace Ht7\Kernel\Config\Models;

use \InvalidArgumentException;
use \Ht7\Kernel\Models\ArrayDotIndexedModel;
use \Ht7\Kernel\Config\Models\GenericConfigDefinitionModel;

/**
 * This model has additional defintions for the handling of the config data.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class GenericDotIndexedConfigModel extends ArrayDotIndexedModel
{

    /**
     * @var     GenericConfigDefinitionModel    The config definitions.
     */
    protected $definitions;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $all = [])
    {
        $this->definitions = [];

        parent::__construct($all);
    }

    /**
     *
     * @return  GenericConfigDefinitionModel
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }

    /**
     *
     * @param array|GenericConfigDefinitionModel $definitions
     */
    public function setDefinitions($definitions)
    {
        if (is_array($definitions)) {
            $definitions = new GenericConfigDefinitionModel($definitions);
        }

        if ($definitions instanceof GenericConfigDefinitionModel) {
            $this->definitions = $definitions;
        } else {
            $e = 'The definitions must be an array or an instance of GenericConfigDefinitionModel'
                    . ' found ' . (is_object($definitions) ? get_class($definitions) : gettype($definitions));

            throw new InvalidArgumentException($e);
        }
    }

}
