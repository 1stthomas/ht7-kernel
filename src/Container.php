<?php

namespace Ht7\Kernel;

use \InvalidArgumentException;
use \Ht7\Base\Lists\Hashable;
use \Ht7\Base\Lists\HashableHashList;
use \Ht7\Base\Lists\HashList;

/**
 * Description of Container
 *
 * @author Thomas Pluess
 */
class Container extends HashList
{

    static protected $instance;

    public function __construct(array $data = [])
    {
        if (empty($data)) {
            $items = [
                new HashableHashList('classes'),
                new HashableHashList('instances'),
                new HashableHashList('paths')
            ];

            $data = array_merge($items, $data);
        }

        parent::__construct($data);
    }

    public function addPlain(string $index, $value)
    {
        $parts = $this->getIndexParts($index);

        if (!($value instanceof Hashable)) {
            $value = (
                    new class ($parts[1], $value) implements Hashable
                    {

                        protected $hash;
                        protected $value;

                        public function __construct(string $hash, $value)
                        {
                            $this->hash = $hash;
                            $this->value = $value;
                        }

                        public function getHash()
                        {
                            return $this->hash;
                        }

                        public function getValue()
                        {
                            return $this->value;
                        }
                    });
        }

        $category = $this->getAll()[$parts[0]];
        $category->add($value);

        return $this;
    }

    public function get($index)
    {
        $parts = $this->getIndexParts($index);
        $category = parent::get($parts[0]);

        return $category->get($parts[1])->getValue();
    }

    public function getIndexParts(string $index)
    {
        $parts = explode('.', $index);

        if (count($parts) !== 2) {
            if (count($parts) === 0) {
                $e = 'The index needs a category separated by a dot.';

                throw new InvalidArgumentException($e);
            } else {
                print_r($parts);
                $e = 'Somthing is wrong with the index.';

                throw new InvalidArgumentException($e);
            }
        }

        return $parts;
    }

    /**
     * Get the singleton of the Kernel Container.
     *
     * @return \Ht7\CmsSimple\Kernel\Container      The container used by the
     *                                              cms kernel.
     */
    public static function getInstance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function has($index)
    {
        $parts = $this->getIndexParts($index);

        if (parent::has($parts[0])) {
            return parent::get($parts[0])->get($parts[1]);
        }
    }

    public function remove($index)
    {
        $parts = $this->getIndexParts($index);

        parent::get($parts[0])->remove($parts[1]);
    }

}
