<?php

namespace Deimos\Morphy\Gram\Info;

class AncodeCache extends Decorator
{

    public
        $hits = 0,
        $miss = 0;

    protected
        $cache;

    public $inner;

    function __construct(InfoInterface $inner, $resource)
    {
//        parent::__construct($inner);

        $this->inner = $inner;
        if (false === ($this->cache = unserialize($resource->read(0, $resource->getFileSize())))) {
            // todo : phpMorphy_Exception
            throw new \Exception("Can`t read ancodes cache");
        }
    }

    function readAncodes($info)
    {
        $offset = $info['offset'];

        if (isset($this->cache[$offset])) {
            $this->hits++;

            return $this->cache[$offset];
        } else {
            // in theory misses never occur
            $this->miss++;

            return parent::readAncodes($info);
        }
    }

}