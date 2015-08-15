<?php

namespace Deimos\Morphy;

class Morphy
{

    public $path;

    /**
     * @var Bundles\File
     */
    private $bundle = null;

    /**
     * @var Storages\Factory
     */
    private $factory = null;

    public $options = array(
        'shm' => array(),
        'graminfo_as_text' => true,
        'storage' => Defines\Storage::File,
        'common_source' => null, // fixme: Def
        'predict_by_suffix' => true,
        'predict_by_db' => true,
        'use_ancodes_cache' => false,
        'resolve_ancodes' => null // fixme: Def
    );

    public function __construct($dir, $lang = null, $options = array())
    {

        $this->options = $options + $this->options;

        $options = &$this->options;

        $this->bundle = new Bundles\File($dir, $lang);
        $this->factory = new Storages\Factory($options);

        $commonAutomat = $this->bundle->getCommonAutomatFile();
        $storage = $this->factory->open($options['storage'], $commonAutomat);

    }

}