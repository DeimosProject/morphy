<?php

namespace Deimos\Morphy\Bundles;

class File
{
    public $path = null;
    public $lang = null;

    public function __construct($path, $lang)
    {
        if (mb_substr($path, -1) == '/') {
            $this->path = $path;
        }
        else {
            $this->path = $path . '/';
        }
        // TODO : Exception
        if (!$lang)
            throw new \Exception();
        $this->lang = $lang;
    }

    private function genFileName($name)
    {
        return $this->path . $name . '.' . $this->lang . '.bin';
    }

    public function getCommonAutomatFile()
    {
        return $this->genFileName('common_aut');
    }

    public function getPredictAutomatFile()
    {
        return $this->genFileName('predict_aut');
    }

    public function getGramInfoFile()
    {
        return $this->genFileName('morph_data');
    }

    public function getGramInfoAncodesCacheFile()
    {
        return $this->genFileName('morph_data_ancodes_cache');
    }

    public function getAncodesMapFile()
    {
        return $this->genFileName('morph_data_ancodes_map');
    }

    public function getGramTabFile()
    {
        return $this->genFileName('gramtab');
    }

    public  function getGramTabFileWithTextIds()
    {
        return $this->genFileName('gramtab_txt');
    }

}