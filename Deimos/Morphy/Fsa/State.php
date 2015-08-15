<?php

namespace Deimos\Morphy\Fsa;

class State
{
    protected
        $fsa,
        $transes,
        $raw_transes;

    function phpMorphy_State(phpMorphy_Fsa_Interface $fsa, $index)
    {
        $this->fsa = $fsa;

        $this->raw_transes = $fsa->readState($index);
        $this->transes = $fsa->unpackTranses($this->raw_transes);
    }

    function getLinks()
    {
        $result = array();

        for ($i = 0, $c = count($this->transes); $i < $c; $i++) {
            $trans = $this->transes[$i];

            if (!$trans['term']) {
                $result[] = $this->createNormalLink($trans, $this->raw_transes[$i]);
            } else {
                $result[] = $this->createAnnotLink($trans, $this->raw_transes[$i]);
            }
        }

        return $result;
    }

    function getSize()
    {
        return count($this->transes);
    }

    protected function createNormalLink($trans, $raw)
    {
        return new phpMorphy_Link($this->fsa, $trans, $raw);
    }

    protected function createAnnotLink($trans, $raw)
    {
        return new phpMorphy_Link_Annot($this->fsa, $trans, $raw);
    }
}