<?php

namespace Deimos\Morphy\AncodesResolver;

class ToDialingAncodes implements AncodesResolverInterface
{

    protected
        $ancodes_map,
        $reverse_map;

    function __construct($storage)
    {
        if (false === ($this->ancodes_map = unserialize($storage->read(0, $storage->getFileSize())))) {
            // todo
            throw new \Exception("Can`t open phpMorphy => Dialing ancodes map");
        }

        $this->reverse_map = array_flip($this->ancodes_map);
    }

    function unresolve($ancode)
    {
        if (!isset($ancode)) {
            return null;
        }

        if (!isset($this->reverse_map[$ancode])) {
            // todo
            throw new \Exception("Unknwon ancode found '$ancode'");
        }

        return $this->reverse_map[$ancode];
    }

    function resolve($ancodeId)
    {
        if (!isset($ancodeId)) {
            return null;
        }

        if (!isset($this->ancodes_map[$ancodeId])) {
            // todo
            throw new \Exception("Unknwon ancode id found '$ancodeId'");
        }

        return $this->ancodes_map[$ancodeId];
    }

}