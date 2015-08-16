<?php

namespace Deimos\Morphy\Storages;

class Factory
{

    public $shm_options;

    public function __construct($shmOptions = array())
    {
        $this->shm_options = $shmOptions;
    }

    /**
     * @param $type
     * @param $path
     * @param bool|false $proxy
     * @return Types\Proxy|Types\File
     * @throws \Exception
     */
    public function open($type, $path, $proxy = false)
    {
        $class = __NAMESPACE__ . '\\Types\\' . $type;
        if (!class_exists($class))
            throw new \Exception();

        if ($proxy)
            return new Types\Proxy(new $class($path), $path);

        return new $class($path);
    }

}