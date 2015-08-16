<?php

namespace Deimos\Morphy\GramInfo;

class Decorator implements GramInfoInterface
{
    /**
     * @var GramInfoInterface
     */
    protected $info;

    public function Decorator(GramInfoInterface $info)
    {
        $this->info = $info;
    }

    public function readGramInfoHeader($offset)
    {
        return $this->info->readGramInfoHeader($offset);
    }

    public function getGramInfoHeaderSize($offset = null)
    {
        return $this->info->getGramInfoHeaderSize($offset);
    }

    public function readAncodes($info)
    {
        return $this->info->readAncodes($info);
    }

    public function readFlexiaData($info)
    {
        return $this->info->readFlexiaData($info);
    }

    public function readAllGramInfoOffsets()
    {
        return $this->info->readAllGramInfoOffsets();
    }

    public function readAllPartOfSpeech()
    {
        return $this->info->readAllPartOfSpeech();
    }

    public function readAllGrammems()
    {
        return $this->info->readAllGrammems();
    }

    public function readAllAncodes()
    {
        return $this->info->readAllAncodes();
    }

    public function getLocale()
    {
        return $this->info->getLocale();
    }

    public function getEncoding()
    {
        return $this->info->getEncoding();
    }

    public function getCharSize()
    {
        return $this->info->getCharSize();
    }

    public function getEnds()
    {
        return $this->info->getEnds();
    }

    public function getHeader()
    {
        return $this->info->getHeader();
    }
}