<?php

namespace Deimos\Morphy\Gram\Info;

class RuntimeCaching extends Decorator
{

    protected
        $flexia = array(),
        $ancodes = array();

    function readFlexiaData($info)
    {
        $offset = $info['offset'];

        if (!isset($this->flexia_all[$offset])) {
            $this->flexia_all[$offset] = $this->info->readFlexiaData($info);
        }

        return $this->flexia_all[$offset];
    }

}