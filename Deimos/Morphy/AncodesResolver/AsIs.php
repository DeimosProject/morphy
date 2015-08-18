<?php

namespace Deimos\Morphy\AncodesResolver;

class AsIs implements AncodesResolverInterface
{

    // This ctor for ReflectionClass::newInstanceArgs($args) with $args = array()
    function __construct()
    {
    }

    function resolve($ancodeId)
    {
        return $ancodeId;
    }

    function unresolve($ancode)
    {
        return $ancode;
    }

}