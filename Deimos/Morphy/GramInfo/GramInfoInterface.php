<?php

namespace Deimos\Morphy\GramInfo;

interface GramInfoInterface
{

    public function getLocale();

    public function getEncoding();

    public function getCharSize();

    public function getEnds();

    public function readGramInfoHeader($offset);

    public function getGramInfoHeaderSize();

    public function readAncodes($info);

    public function readFlexiaData($info);

    public function readAllGramInfoOffsets();

    public function getHeader();

    public function readAllPartOfSpeech();

    public function readAllGrammems();

    public function readAllAncodes();

}