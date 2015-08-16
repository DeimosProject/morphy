<?php

namespace Deimos\Morphy\Gram\Tab;

class Proxy implements TabInterface
{

    protected $storage;

    function __construct($storage)
    {
        $this->storage = $storage;
    }

    function getGrammems($ancodeId)
    {
        return $this->__obj->getGrammems($ancodeId);
    }

    function getPartOfSpeech($ancodeId)
    {
        return $this->__obj->getPartOfSpeech($ancodeId);
    }

    function resolveGrammemIds($ids)
    {
        return $this->__obj->resolveGrammemIds($ids);
    }

    function resolvePartOfSpeechId($id)
    {
        return $this->__obj->resolvePartOfSpeechId($id);
    }

    function includeConsts()
    {
        return $this->__obj->includeConsts();
    }

    function ancodeToString($ancodeId, $commonAncode = null)
    {
        return $this->__obj->ancodeToString($ancodeId, $commonAncode);
    }

    function stringToAncode($string)
    {
        return $this->__obj->stringToAncode($string);
    }

    function toString($partOfSpeechId, $grammemIds)
    {
        return $this->__obj->toString($partOfSpeechId, $grammemIds);
    }

    function __get($name)
    {
        if ($name === '__obj') {
            $this->__obj = new Tab($this->storage);
            unset($this->storage);
            return $this->__obj;
        }

        // todo : exception
        throw new \Exception("Invalid prop name '$name'");
    }

}