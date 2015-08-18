<?php

namespace Deimos\Morphy\AncodesResolver;

class ToText implements AncodesResolverInterface
{

    protected $gramtab;

    function __construct(\Deimos\Morphy\Gram\Tab\TabInterface $gramtab)
    {
        $this->gramtab = $gramtab;
    }

    function resolve($ancodeId)
    {
        if (!isset($ancodeId)) {
            return null;
        }

        return $this->gramtab->ancodeToString($ancodeId);
    }

    function unresolve($ancode)
    {
        return $this->gramtab->stringToAncode($ancode);
        //throw new phpMorphy_Exception("Can`t convert grammar info in text into ancode id");
    }

}