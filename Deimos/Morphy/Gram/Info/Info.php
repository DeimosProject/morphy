<?php

namespace Deimos\Morphy\Gram\Info;

abstract class Info implements InfoInterface
{

    const HEADER_SIZE = 128;

    protected
        $resource,
        $header,
        $ends,
        $ends_size;

    public function __construct($resource, $header)
    {
        $this->resource = $resource;
        $this->header = $header;

        $this->ends = str_repeat("\0", $header['char_size'] + 1);
        $this->ends_size = /*$GLOBALS['__phpmorphy_*/strlen($this->ends);
    }


    static function create($storage, $proxy = false)
    {
        if ($proxy) {
            // todo : phpMorphy_GramInfo_Proxy
            return new Proxy($storage);
        }

        $header = self::readHeader(
            $storage->read(0, self::HEADER_SIZE)
        );

        if (!self::validateHeader($header)) {
            // todo : phpMorphy_Exception
            throw new \Exception('Invalid graminfo format');
        }

        $storage_type = $storage->getTypeAsString();

        /** @var todo namespace $file_path */
        $file_path = dirname(__FILE__) . "/access/graminfo_{$storage_type}.php";
        $class = 'phpMorphy_GramInfo_' . ucfirst($storage_type);

        require_once($file_path);
        return new $class($storage->getResource(), $header);
    }

    function getLocale()
    {
        return $this->header['lang'];
    }

    function getEncoding()
    {
        return $this->header['encoding'];
    }

    function getCharSize()
    {
        return $this->header['char_size'];
    }

    function getEnds()
    {
        return $this->ends;
    }

    function getHeader()
    {
        return $this->header;
    }

    static protected function readHeader($headerRaw)
    {
        $header = unpack(
            'Vver/Vis_be/Vflex_count_old/' .
            'Vflex_offset/Vflex_size/Vflex_count/Vflex_index_offset/Vflex_index_size/' .
            'Vposes_offset/Vposes_size/Vposes_count/Vposes_index_offset/Vposes_index_size/' .
            'Vgrammems_offset/Vgrammems_size/Vgrammems_count/Vgrammems_index_offset/Vgrammems_index_size/' .
            'Vancodes_offset/Vancodes_size/Vancodes_count/Vancodes_index_offset/Vancodes_index_size/' .
            'Vchar_size/',
            $headerRaw
        );

        $offset = 24 * 4;
        $len = ord(/*$GLOBALS['__phpmorphy_*/substr($headerRaw, $offset++, 1));
        $header['lang'] = rtrim(/*$GLOBALS['__phpmorphy_*/substr($headerRaw, $offset, $len));

        $offset += $len;

        $len = ord(/*$GLOBALS['__phpmorphy_*/substr($headerRaw, $offset++, 1));
        $header['encoding'] = rtrim(/*$GLOBALS['__phpmorphy_*/substr($headerRaw, $offset, $len));

        return $header;
    }

    static protected function validateHeader($header)
    {
        if (
            3 != $header['ver'] ||
            1 == $header['is_be']
        ) {
            return false;
        }

        return true;
    }

    protected function cleanupCString($string)
    {
        if (false !== ($pos = /*$GLOBALS['__phpmorphy_strpos']*/strpos($string, $this->ends))) {
            $string = /*$GLOBALS['__phpmorphy_*/substr($string, 0, $pos);
        }

        return $string;
    }

    abstract protected function readSectionIndex($offset, $count);

    protected function readSectionIndexAsSize($offset, $count, $total_size)
    {
        if (!$count) {
            return array();
        }

        $index = $this->readSectionIndex($offset, $count);
        $index[$count] = $index[0] + $total_size;

        for ($i = 0; $i < $count; $i++) {
            $index[$i] = $index[$i + 1] - $index[$i];
        }

        unset($index[$count]);

        return $index;
    }

}