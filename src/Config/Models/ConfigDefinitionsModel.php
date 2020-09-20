<?php

namespace Ht7\Kernel\Config\Models;

use \InvalidArgumentException;
use \Ht7\Kernel\Models\ArrayDotIndexedModel;
use \Ht7\Kernel\Config\Models\GenericConfigModel;

/**
 * This model has additional definitions for the handling of the config data.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class ConfigDefinitionsModel extends ArrayDotIndexedModel
{

    /**
     * @var     GenericConfigModel      The default config definitions.
     */
    protected $defaults;

    /**
     * These settings belong to the config_definitions category. They define
     * e.g. loading and saving options.
     *
     * @var     GenericConfigModel      The config definitions.
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
     * Get the default config definitions.
     *
     * @return  GenericConfigModel      The default config definitions.
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * Get the config definitions.
     *
     * @return  GenericConfigModel      The config definitions.
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }

    /**
     * Set the config definitions.
     *
     * @param array|GenericConfigModel  $defaults   The default config definitions.
     */
    public function setDefaults($defaults)
    {
        if (is_array($defaults)) {
            $defaults = new GenericConfigModel($defaults);
        }

        if ($defaults instanceof GenericConfigModel) {
            $this->defaults = $defaults;
        } else {
            $e = 'The defaults must be an array or an instance of ' . GenericConfigModel::class
                    . ' found ' . (is_object($defaults) ? get_class($defaults) : gettype($defaults));

            throw new InvalidArgumentException($e);
        }
    }

    /**
     * Set the config definitions.
     *
     * @param array|GenericConfigModel  $definitions    The config definitions.
     */
    public function setDefinitions($definitions)
    {
        if (is_array($definitions)) {
            $definitions = new GenericConfigModel($definitions);
        }

        if ($definitions instanceof GenericConfigModel) {
            $this->definitions = $definitions;
        } else {
            $e = 'The definitions must be an array or an instance of ' . GenericConfigModel::class
                    . ' found ' . (is_object($definitions) ? get_class($definitions) : gettype($definitions));

            throw new InvalidArgumentException($e);
        }
    }

}
