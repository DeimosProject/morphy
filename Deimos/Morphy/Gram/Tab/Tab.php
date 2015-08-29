<?php

namespace Deimos\Morphy\Gram\Tab;

class Tab implements TabInterface
{

    protected
        $data,
        $ancodes,
        $grammems,
        $poses;

    public function __construct($storage)
    {
        $this->data = unserialize($storage->read(0, $storage->getFileSize()));

        if (false === $this->data) {
            // todo
            throw new \Exception("Broken gramtab data");
        }

        $this->grammems = $this->data['grammems'];
        $this->poses = $this->data['poses'];
        $this->ancodes = $this->data['ancodes'];
    }

    /**
     * @deprecated new __cons
     */
    static function create($storage)
    {
        return new self($storage);
    }

    function getGrammems($ancodeId)
    {
        if (!isset($this->ancodes[$ancodeId])) {
            // todo
            throw new \Exception("Invalid ancode id '$ancodeId'");
        }

        return $this->ancodes[$ancodeId]['grammem_ids'];
    }

    function getPartOfSpeech($ancodeId)
    {
        if (!isset($this->ancodes[$ancodeId])) {
            // todo exception
            throw new \Exception("Invalid ancode id '$ancodeId'");
        }

        return $this->ancodes[$ancodeId]['pos_id'];
    }

    function resolveGrammemIds($ids)
    {
        if (is_array($ids)) {
            $result = array();

            foreach ($ids as $id) {
                if (!isset($this->grammems[$id])) {
                    // todo
                    throw new \Exception("Invalid grammem id '$id'");
                }

                $result[] = $this->grammems[$id]['name'];
            }

            return $result;
        }
        else {
            if (!isset($this->grammems[$ids])) {
                // todo
                throw new \Exception("Invalid grammem id '$ids'");
            }

            return $this->grammems[$ids]['name'];
        }
    }

    function resolvePartOfSpeechId($id)
    {
        if (!isset($this->poses[$id])) {
            // todo
            throw new \Exception("Invalid part of speech id '$id'");
        }

        return $this->poses[$id]['name'];
    }

    function includeConsts()
    {
        require_once(PHPMORPHY_DIR . '/gramtab_consts.php');
    }

    function ancodeToString($ancodeId, $commonAncode = null)
    {
        if (isset($commonAncode)) {
            $commonAncode = implode(',', $this->getGrammems($commonAncode)) . ',';
        }

        return
            $this->getPartOfSpeech($ancodeId) . ' ' .
            $commonAncode .
            implode(',', $this->getGrammems($ancodeId));
    }

    protected function findAncode($partOfSpeech, $grammems)
    {
    }

    function stringToAncode($string)
    {
        if (!isset($string)) {
            return null;
        }

        if (!isset($this->__ancodes_map[$string])) {
            // todo
            throw new \Exception("Ancode with '$string' graminfo not found");
        }

        return $this->__ancodes_map[$string];
    }

    function toString($partOfSpeechId, $grammemIds)
    {
        return $partOfSpeechId . ' ' . implode(',', $grammemIds);
    }

    protected function buildAncodesMap()
    {
        $result = array();

        foreach ($this->ancodes as $ancode_id => $data) {
            $key = $this->toString($data['pos_id'], $data['grammem_ids']);

            $result[$key] = $ancode_id;
        }

        return $result;
    }

    function __get($propName)
    {
        switch ($propName) {
            case '__ancodes_map':
                $this->__ancodes_map = $this->buildAncodesMap();
                return $this->__ancodes_map;
        }

        throw new \Exception("Unknown '$propName' property");
    }

}