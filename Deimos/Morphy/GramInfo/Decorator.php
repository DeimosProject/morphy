<?php

namespace Deimos\Morphy\GramInfo;

class Decorator implements GramInfoInterface
{
    /**
     * @var GramInfoInterface
     */
    protected $info;

    function phpMorphy_GramInfo_Decorator(GramInfoInterface $info)
    {
        $this->info = $info;
    }

    function readGramInfoHeader($offset)
    {
        return $this->info->readGramInfoHeader($offset);
    }

    function getGramInfoHeaderSize()
    {
        // todo : return $this->info->getGramInfoHeaderSize($offset);
        return null;
    }

    function readAncodes($info)
    {
        return $this->info->readAncodes($info);
    }

    function readFlexiaData($info)
    {
        return $this->info->readFlexiaData($info);
    }

    function readAllGramInfoOffsets()
    {
        return $this->info->readAllGramInfoOffsets();
    }

    function readAllPartOfSpeech()
    {
        return $this->info->readAllPartOfSpeech();
    }

    function readAllGrammems()
    {
        return $this->info->readAllGrammems();
    }

    function readAllAncodes()
    {
        return $this->info->readAllAncodes();
    }

    function getLocale()
    {
        return $this->info->getLocale();
    }

    function getEncoding()
    {
        return $this->info->getEncoding();
    }

    function getCharSize()
    {
        return $this->info->getCharSize();
    }

    function getEnds()
    {
        return $this->info->getEnds();
    }

    function getHeader()
    {
        return $this->info->getHeader();
    }
}