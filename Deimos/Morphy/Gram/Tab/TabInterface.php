<?php

namespace Deimos\Morphy\Gram\Tab;

interface TabInterface
{

    function getGrammems($ancodeId);

    function getPartOfSpeech($ancodeId);

    function resolveGrammemIds($ids);

    function resolvePartOfSpeechId($id);

    function includeConsts();

    function ancodeToString($ancodeId, $commonAncode = null);

    function stringToAncode($string);

    function toString($partOfSpeechId, $grammemIds);

}