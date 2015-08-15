<?php

namespace Deimos\Morphy\Storages\Types;

class Proxy
{

    /**
     * @var $storage File
     */
    public $storage;

    public $path;

    public function __construct($storage, $path)
    {
        $this->storage = $storage;
        $this->path = $path;
    }

    public function getType()
    {
        return basename(__CLASS__);
    }

}