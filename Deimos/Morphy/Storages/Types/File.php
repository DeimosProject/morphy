<?php

namespace Deimos\Morphy\Storages\Types;

class File extends \Deimos\Morphy\Storages\Storage
{

    protected function open()
    {
        if (($fh = fopen($this->path, 'rb')) === false) {
            throw new \Deimos\Morphy\Exceptions\File();
        }
        return $fh;
    }

    public function getFileSize()
    {
        if (false === ($stat = fstat($this->resource))) {
            // TODO : Создать новый Exception
            throw new \Exception();
            // TODO : throw new phpMorphy_Exception('Can`t invoke fstat for ' . $this->file_name . ' file');
        }

        return $stat['size'];
    }

    public function readUnsafe($offset, $len)
    {
        if (0 !== fseek($this->resource, $offset)) {
            // TODO : Создать новый Exception
            throw new \Exception();
            // TODO : throw new phpMorphy_Exception("Can`t seek to $offset offset");
        }

        return fread($this->resource, $len);
    }

    public function getType()
    {
        return parent::_getType(__CLASS__);
    }

}