<?php

namespace Deimos\Morphy\AncodesResolver;

interface AncodesResolverInterface
{

    function resolve($ancodeId);

    function unresolve($ancode);
}