<?php

namespace Deimos\Morphy\Fsa;

class Proxy extends Decorator
{
    protected $storage;

    function __construct($storage)
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
