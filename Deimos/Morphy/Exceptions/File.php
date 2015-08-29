<?php

namespace Deimos\Morphy\Exceptions;

use Deimos\Morphy\Defines\Exception;

class File extends \Exception
{
    public function __construct($message = Exception::CantOpenFile, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}