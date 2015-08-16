<?php

namespace Deimos\Morphy\Gram\Info\Proxy;

use Deimos\Morphy\Gram\Info\Decorator;
use Deimos\Morphy\Gram\Info\Info;

class Proxy extends Decorator
{

    /**
     * @var \Deimos\Morphy\Storages\Storage
     */
    protected $storage;

    function __construct($storage)
    {
        $this->storage = $storage;
        unset($this->info);
    }

    function __get($propName)
    {
        if ($propName == 'info') {
            $this->info = Info::create($this->storage, false);
            unset($this->storage);
            return $this->info;
        }
        // todo : phpMorphy_Exception
        throw new \Exception("Unknown prop name '$propName'");
    }

}