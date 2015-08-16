<?php

namespace Deimos\Morphy\Gram\Info\Proxy;

class WithHeader extends Proxy
{

    protected
        $cache,
        $ends;

    function __construct($storage, $cacheFile)
    {
        parent::__construct($storage);

        $this->cache = $this->readCache($cacheFile);
        $this->ends = str_repeat("\0", $this->getCharSize() + 1);
    }

    protected function readCache($fileName)
    {
        if (!is_array($result = include($fileName))) {
            // todo : phpMorphy_Exception
            throw new \Exception("Can`t get header cache from '$fileName' file'");
        }

        return $result;
    }

    function getLocale()
    {
        return $this->cache['lang'];
    }

    function getEncoding()
    {
        return $this->cache['encoding'];
    }

    function getCharSize()
    {
        return $this->cache['char_size'];
    }

    function getEnds()
    {
        return $this->ends;
    }

    function getHeader()
    {
        return $this->cache;
    }

}