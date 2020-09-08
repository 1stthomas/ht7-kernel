<?php

namespace Ht7\Kernel\Config;

use \RuntimeException;

/**
 * Container for the loading sequence of config files.
 *
 * The loading sequence has normally the kernel files at the beginning. It is
 * reverse to the sequence while iterating the storage units on a <code>get($index, $default)</code>
 * call.
 *
 * @author      Thomas Pluess
 * @version     0.0.1
 * @since       0.0.1
 */
class ConfigLoadingSequence
{

    /**
     * The defaults must not be changed. They contian dirs to basic application
     * data of the kernel.
     */
    public const DEFAULTS = 'defaults';

    /**
     * Here must be defined all further loading dirs.
     */
    public const ADDITIONAL = 'additional';

    /**
     * Loading sequence.
     *
     * @var     array           Assoc array with the sequence categories: <code>self::DEFAULTS</code>
     *                          and <code>self::DEFAULTS</code>. These categories
     *                          contain the config path types to the dirs with
     *                          config files in the order of their loading.
     */
    protected $sequence;

    /**
     * Create an instance of the <code>ConfigLoadingSequence</code> class.
     *
     * @param   array   $defaults       An array of config path types. It will be
     *                                  set as the value of <code>self::DEFAULTS</code>.
     * @param   array   $additional     An array of config path types. It will be
     *                                  set as the value of <code>self::ADDITIOMAL</code>.
     * @return  void
     */
    public function __construct(array $defaults, array $additional = [])
    {
        $this->sequence = [];

        $this->setByIndex(self::DEFAULTS, $defaults);

        if (!empty($additional)) {
            $this->setByIndex(self::ADDITIONAL, $additional);
        } else {
            $this->sequence[self::ADDITIONAL] = [];
        }
    }

    /**
     * Add a config path type to the loading sequence. It will be added to the
     * <code>self::ADDITIONAL</code> sequence category.
     *
     * @param   string  $configPathType
     * @return  void
     * @throws  RuntimeException
     */
    public function add(string $configPathType)
    {
        if (in_array($configPathType, $this->getSequence())) {
            $e = 'The config path type ' . $configPathType . ' has already been added.';

            throw new RuntimeException($e);
        }

        $this->sequence['additional'][] = $configPathType;
    }

    /**
     * Add multiple config path types to the loading sequence. They will be added
     * to the <code>self::ADDITIONAL</code> sequence category.
     *
     * @param   array   $configPathTypes    An indexed array of config path types.
     * @return  void
     */
    public function addMultiple(array $configPathTypes)
    {
        foreach ($configPathTypes as $cpt) {
            $this->add($cpt);
        }
    }

    /**
     * Get the loading category by index.
     *
     * @param   string  $index          Supported types: 'defaults', ''.
     * @return  array                   Indexed array of config path types.
     */
    public function get(string $index)
    {
        return $this->sequence[$index];
    }

    /**
     * Get the supported loading sequence categories.
     *
     * @return  array           Indexed array of category names.
     */
    public function getCategories()
    {
        return [
            self::DEFAULTS,
            self::ADDITIONAL,
        ];
    }

    /**
     * Get the whole loading sequence.
     *
     * @return  array                   Indexed array with the config path types
     *                                  as the items in the order of their loading
     *                                  sequence.
     */
    public function getSequence()
    {
        return array_merge(
                $this->sequence[self::DEFAULTS],
                $this->sequence[self::ADDITIONAL]
        );
    }

    /**
     * Get the loading sequence from the kernel to the present config path type.
     *
     * @param   string  $configPathType The limit of the config path type sequence.
     * @return  array                   The loading sequence to the present config
     *                                  path type.
     */
    public function getSequenceTo(string $configPathType)
    {
        $sequence = $this->getSequence();
        $i = count($sequence);

        while ($i > 0) {
            if (array_pop($sequence) === $configPathType) {
                $sequence[] = $configPathType;
            }

            $i--;
        }

        return $sequence;
    }

    /**
     * Set loading dirs to config files by their loading category.
     *
     * @param   string  $index              The loading sequence category.
     * @param   array   $configPathTypes    Indexed array of config path types.
     *                                      Their sequence reflects the one of
     *                                      the loading.
     * @return  void
     * @throws  RuntimeException
     */
    public function setByIndex(string $index, array $configPathTypes)
    {
        if ($index === self::DEFAULTS && !empty($this->sequence[self::DEFAULTS])) {
            $e = 'The defaults have already been set.';

            throw new RuntimeException($e);
        } elseif ($index === self::ADDITIONAL && !empty(array_intersect($this->get('defaults'), $configPathTypes))) {
            $e = 'There are duplicated config path types: ' . implode(', ', $configPathTypes) . '.';

            throw new RuntimeException($e);
        } elseif (!in_array($index, [self::ADDITIONAL, self::DEFAULTS])) {
            $e = 'Undefined sequence category: ' . $index . '.';

            throw new RuntimeException($e);
        }

        $this->sequence[$index] = $configPathTypes;
    }

}
