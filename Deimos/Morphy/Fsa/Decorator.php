<?php

namespace Deimos\Morphy\Fsa;

class Decorator implements FsaInterface
{

    /**
     * @var Fsa
     */
    protected $fsa;

    function phpMorphy_Fsa_Decorator(Fsa $fsa)
    {
        $this->fsa = $fsa;
    }

//    public function __call($name, $arguments) {
//
//        if (!method_exists($this->fsa, $name))
//            throw new \Exception(); // TODO :
//
//        $this->fsa->$name($arguments);
//
//    }

    function getRootTrans()
    {
        return $this->fsa->getRootTrans();
    }

    function getRootState()
    {
        return $this->fsa->getRootState();
    }

    function getAlphabet()
    {
        return $this->fsa->getAlphabet();
    }

    function getAnnot($trans)
    {
        return $this->fsa->getAnnot($trans);
    }

    function walk($start, $word, $readAnnot = true)
    {
        return $this->fsa->walk($start, $word, $readAnnot);
    }

    function collect($start, $callback, $readAnnot = true, $path = '')
    {
        return $this->fsa->collect($start, $callback, $readAnnot, $path);
    }

    function readState($index)
    {
        return $this->fsa->readState($index);
    }

    function unpackTranses($transes)
    {
        return $this->fsa->unpackTranses($transes);
    }

}