<?php

namespace Deimos\Morphy\WordDescriptor;

use Deimos\Morphy\Morphier\Helper;

class Collection implements \Countable, \IteratorAggregate, \ArrayAccess
{
    protected
        $word,
        $descriptors = array(),
        $helper;

    function __construct($word, $annots, Helper $helper)
    {
        $this->word = (string)$word;
        $this->annots = false === $annots ? false : $helper->decodeAnnot($annots, true);

        $this->helper = $helper;

        if (false !== $this->annots) {
            foreach ($this->annots as $annot) {
                $this->descriptors[] = $this->createDescriptor($word, $annot, $helper);
            }
        }
    }

    protected function createDescriptor($word, $annot, Helper $helper)
    {
        return new phpMorphy_WordDescriptor($word, $annot, $helper);
    }

    function getDescriptor($index)
    {
        if (!$this->offsetExists($index)) {
            // todo
            throw new \Exception("Invalid index '$index' specified");
        }

        return $this->descriptors[$index];
    }

    function getByPartOfSpeech($poses)
    {
        $result = array();
        settype($poses, 'array');

        foreach ($this as $desc) {
            if ($desc->hasPartOfSpeech($poses)) {
                $result[] = $desc;
            }
        }

//        return count($result) ? $result : false;
        return $result;
    }

    function offsetExists($off)
    {
        return isset($this->descriptors[$off]);
    }

    function offsetUnset($off)
    {
        // todo
        throw new \Exception(__CLASS__ . " is not mutable");
    }

    function offsetSet($off, $value)
    {
        // todo
        throw new \Exception(__CLASS__ . " is not mutable");
    }

    function offsetGet($off)
    {
        return $this->getDescriptor($off);
    }

    function count()
    {
        return count($this->descriptors);
    }

    function getIterator()
    {
        return new \ArrayIterator($this->descriptors);
    }
}