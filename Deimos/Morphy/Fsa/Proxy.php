<?php

namespace Deimos\Morphy\Fsa;

use Deimos\Morphy\Storages\Storage;

class Proxy extends Decorator
{
    protected $storage;

    function __construct(Storage $storage)
    {
        $this->storage = $storage;
        unset($this->fsa);
    }

    function __get($propName)
    {
        if ($propName == 'fsa') {
            $this->fsa = Fsa::create($this->storage, false);

            unset($this->storage);
            return $this->fsa;
        }
        // TODO
        throw new \Exception("Unknown prop name '$propName'");
    }
}
