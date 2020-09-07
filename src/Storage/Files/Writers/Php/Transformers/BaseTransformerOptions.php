<?php

namespace Ht7\Kernel\Storage\Files\Writers\Php\Transformers;

//use \Ht7\Kernel\Models\ArrayDotIndexedModel;
//use \Ht7\Kernel\Utility\Values\Sanitizers\StringSanitizeTypes;

/**
 * Description of ExportOptions
 *
 * @author Thomas Pluess
 */
class BaseTransformerOptions
{

    /**
     *
     * @var     int                 The base transformation flags. Use the ones from
     *                              <code>Ht7\Kernel\Storage\Files\Writers\Php\PhpStringWriterOptionFlags</code>.
     */
    protected $flags;

    /**
     * Class wrapper is level 0, method and property definitions are level 1 and so on.
     *
     * @var     int                 The indention per code level.
     */
    protected $indention;

    /**
     * Create an instance of the <code>BaseWriterOptions</code> class.
     *
     * @param   int     $indention  The indention per code level.
     * @param   int     $flags      The base transformation flags. Use the ones from
     *                              <code>Ht7\Kernel\Storage\Files\Writers\Php\PhpStringWriterOptionFlags</code>.
     */
    public function __construct($indention = 4, int $flags = 0)
    {
        $this->setIndention($indention);
        $this->setFlags($flags);
    }

    /**
     * Get the defined base transformation flags.
     *
     * @return  int                     The base transformation flags.
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * Get the indention per code level.
     *
     * @return  int                     The indention.
     */
    public function getIndention()
    {
        return $this->indention;
    }

    /**
     * Set the base transformation flags.
     *
     * @param   int     $flags          The base transformation flags. Use the
     *                                  ones from
     *                                  <code>Ht7\Kernel\Storage\Files\Writers\Php\PhpStringWriterOptionFlags</code>.
     * @return  void
     */
    public function setFlags(int $flags)
    {
        $this->flags = $flags;
    }

    /**
     * Set the bas indention per code level.
     *
     * @param   int     $indention      The indention to set.
     * @return  void
     */
    public function setIndention(int $indention)
    {
        $this->indention = $indention;
    }

}
