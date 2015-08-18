<?php

namespace Deimos\Morphy\AncodesResolver;

class Proxy implements AncodesResolverInterface
{

    protected
        $args, $class;

    //$__obj;


    function __construct($class, $ctorArgs)
    {
        $this->class = $class;
        $this->args = $ctorArgs;
    }

    function unresolve($ancode)
    {
        return $this->__obj->unresolve($ancode);
    }

    function resolve($ancodeId)
    {
        return $this->__obj->resolve($ancodeId);
    }

    static function instantinate($class, $args)
    {
        $ref = new \ReflectionClass($class);
        return $ref->newInstanceArgs($args);
    }

    function __get($propName)
    {
        if ($propName === '__obj') {
            $this->__obj = $this->instantinate($this->class, $this->args);

            unset($this->args);
            unset($this->class);

            return $this->__obj;
        }

        // todo
        throw new \Exception("Unknown '$propName' property");
    }

}