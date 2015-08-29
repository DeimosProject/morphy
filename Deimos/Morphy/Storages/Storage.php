<?php

namespace Deimos\Morphy\Storages;

abstract class Storage
{

    protected $resource, $path;

    protected abstract function open();

    public abstract function readUnsafe($offset, $len);

    public abstract function getFileSize();

    public function __construct($path)
    {
        $this->path = $path;
        $this->resource = $this->open();
    }

    public function getFileName()
    {
        return $this->path;
    }

    public function getResource()
    {
        return $this->resource;
    }

    protected function _getType($name)
    {
        return basename($name);
    }

    public function getType()
    {
        return $this->_getType(__CLASS__);
    }

    public function read($offset, $len, $exactLength = true)
    {
        if ($offset >= $this->getFileSize()) {
            // TODO : Создать новый Exception
            throw new \Exception();
            // TODO : throw new phpMorphy_Exception("Can`t read $len bytes beyond end of '" . $this->getFileName() . "' file, offset = $offset, file_size = " . $this->getFileSize());
        }

        try {
            $result = $this->readUnsafe($offset, $len);
        }
        catch (\Exception $e) {
            // TODO : Создать новый Exception
            throw new \Exception();
            // TODO : throw new phpMorphy_Exception("Can`t read $len bytes at $offset offset, from '" . $this->getFileName() . "' file: " . $e->getMessage());
        }

        if ($exactLength && strlen($result) < $len) {
            // TODO : Создать новый Exception
            throw new \Exception();
            // TODO : throw new phpMorphy_Exception("Can`t read $len bytes at $offset offset, from '" . $this->getFileName() . "' file");
        }

        return $result;
    }

}