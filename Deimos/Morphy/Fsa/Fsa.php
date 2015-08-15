<?php

namespace Deimos\Morphy\Fsa;

abstract class Fsa
{

    const HEADER_SIZE = 128;

    protected
        $resource,
        $header,
        $fsa_start,
        $root_trans,
        $alphabet;

    public function __construct($resource, $header)
    {
        $this->resource = $resource;
        $this->header = $header;
        $this->fsa_start = $header['fsa_offset'];
        $this->root_trans = $this->readRootTrans();
    }

    static function create($storage, $lazy)
    {
        if ($lazy) {
            // TODO : Fixme
            throw new \Exception();
//            return new phpMorphy_Fsa_Proxy($storage);
        }

        $header = self::readHeader(
            $storage->read(0, self::HEADER_SIZE, true)
        );

        if (!self::validateHeader($header)) {
            // TODO:
            throw new \Exception('Invalid fsa format');
        }

        if ($header['flags']['is_sparse']) {
            $type = 'Sparse';
        }
        else if ($header['flags']['is_tree']) {
            $type = 'Tree';
        }
        else {
            // TODO :
            throw new \Exception('Only sparse or tree fsa`s supported');
        }

        $storage_type = $storage->getType();
        $class = '\\' . dirname(__CLASS__) . '\\Access\\' . ucfirst($type) . '\\' . ucfirst($storage_type);

        return new $class(
            $storage->getResource(),
            $header
        );
    }

    function getRootTrans()
    {
        return $this->root_trans;
    }

    function getRootState()
    {
        return $this->createState($this->getRootStateIndex());
    }

    function getAlphabet()
    {
        if (!isset($this->alphabet)) {
            $this->alphabet = str_split($this->readAlphabet());
        }

        return $this->alphabet;
    }

    protected function createState($index)
    {
        return new State($this, $index);
    }

    static protected function readHeader($headerRaw)
    {
        if (mb_strlen($headerRaw) != self::HEADER_SIZE) {
            throw new \phpMorphy_Exception('Invalid header string given');
        }

        $header = unpack(
            'a4fourcc/Vver/Vflags/Valphabet_offset/Vfsa_offset/Vannot_offset/Valphabet_size/Vtranses_count/Vannot_length_size/' .
            'Vannot_chunk_size/Vannot_chunks_count/Vchar_size/Vpadding_size/Vdest_size/Vhash_size',
            $headerRaw
        );

        if (false === $header) {
            // TODO phpMorphy_
            throw new \Exception('Can`t unpack header');
        }

        $flags = array();
        $raw_flags = $header['flags'];
        $flags['is_tree'] = $raw_flags & 0x01 ? true : false;
        $flags['is_hash'] = $raw_flags & 0x02 ? true : false;
        $flags['is_sparse'] = $raw_flags & 0x04 ? true : false;
        $flags['is_be'] = $raw_flags & 0x08 ? true : false;

        $header['flags'] = $flags;

        $header['trans_size'] = $header['char_size'] + $header['padding_size'] + $header['dest_size'] + $header['hash_size'];

        return $header;
    }

    // static
    static protected function validateHeader($header)
    {
        if (
            'meal' != $header['fourcc'] ||
            3 != $header['ver'] ||
            $header['char_size'] != 1 ||
            $header['padding_size'] > 0 ||
            $header['dest_size'] != 3 ||
            $header['hash_size'] != 0 ||
            $header['annot_length_size'] != 1 ||
            $header['annot_chunk_size'] != 1 ||
            $header['flags']['is_be'] ||
            $header['flags']['is_hash'] ||
            1 == 0
        ) {
            return false;
        }

        return true;
    }

    protected function getRootStateIndex()
    {
        return 0;
    }

    abstract protected function readRootTrans();

    abstract protected function readAlphabet();

}