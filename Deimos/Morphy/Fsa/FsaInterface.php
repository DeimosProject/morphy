<?php

namespace Deimos\Morphy\Fsa;

interface FsaInterface
{

    public function getRootTrans();

    public function getRootState();

    public function getAlphabet();

    public function getAnnot($trans);

    public function walk($trans, $word, $readAnnot = true);

    public function collect($startNode, $callback, $readAnnot = true, $path = '');

    public function readState($index);

    public function unpackTranses($rawTranses);

}