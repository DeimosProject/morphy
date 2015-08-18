<?php

namespace Deimos\Morphy;

use Deimos\Morphy\Fsa\Fsa;

class Morphy
{

    const RESOLVE_ANCODES_AS_TEXT = 0;
    const RESOLVE_ANCODES_AS_DIALING = 1;
    const RESOLVE_ANCODES_AS_INT = 2;

    const NORMAL = 0;
    const IGNORE_PREDICT = 2;
    const ONLY_PREDICT = 3;

    const PREDICT_BY_NONE = 'none';
    const PREDICT_BY_SUFFIX = 'by_suffix';
    const PREDICT_BY_DB = 'by_db';

    public $path;

    /**
     * @var Bundles\File
     */
    private $bundle = null;

    /**
     * @var Storages\Factory
     */
    private $factory = null;

    public $commonFsa;
    public $predictFsa;

    public $options = array(
        'shm' => array(),
        'graminfo_as_text' => true,
        'storage' => Defines\Storage::File,
        'common_source' => null, // fixme: Def
        'predict_by_suffix' => true,
        'predict_by_db' => true,
        'use_ancodes_cache' => false,
        'resolve_ancodes' => null // fixme: Def
    );

    public function __construct($dir, $lang = null, $options = array())
    {

        $this->options = $options + $this->options;

        $options = &$this->options;

        $this->bundle = new Bundles\File($dir, $lang);
        $this->factory = new Storages\Factory($options);

        // fsa
        $commonAutomat = $this->bundle->getCommonAutomatFile();
        $storage = $this->factory->open($options['storage'], $commonAutomat);
        $this->commonFsa = Fsa::create($storage);

        $predictAutomat = $this->bundle->getPredictAutomatFile();
        $storage = $this->factory->open($options['storage'], $predictAutomat, true);
        $this->predictFsa = Fsa::create($storage, true);

        // graminfo
        $gramInfoFile = $this->bundle->getGramInfoFile();
        $storage = $this->factory->open($options['storage'], $gramInfoFile, true);
        $gramInfo = $this->createGramInfo($storage);

        $gramTabPath = $this->bundle->getGramTabFile();
        if ($options['graminfo_as_text']) {
            $gramTabPath = $this->bundle->getGramTabFileWithTextIds();
        }

        // gramtab
        $gramTab = $this->createGramTab(
            $this->factory->open(
                $options['storage'],
                $gramTabPath,
                true
            )
        ); // always lazy

        // common source
        //$this->__common_source = $this->createCommonSource($bundle, $this->options['common_source']);

        $this->helper = $this->createMorphierHelper(
            $gramInfo,
            $gramTab,
            $options['graminfo_as_text']
        );

    }

    protected function createGramTab($storage)
    {
        // todo:
        return new Gram\Tab\Proxy($storage);
    }

    protected function createMorphierHelper(
        Gram\Info\InfoInterface $gramInfo,
        Gram\Tab\TabInterface $gramTab,
        $gramInfoAsText
    )
    {
        return new Morphier\Helper(
            $gramInfo,
            $gramTab,
            $this->createAncodesResolver($gramTab, true),
            $gramInfoAsText
        );
    }

    protected function createAncodesResolver(Gram\Tab\TabInterface $gramtab, $lazy)
    {
        $result = $this->createAncodesResolverInternal($gramtab);

        if ($lazy) {
            return new AncodesResolver\Proxy($result[0], $result[1]);
        } else {
            return AncodesResolver\Proxy::instantinate($result[0], $result[1]);
        }
    }

    protected function createAncodesResolverInternal(Gram\Tab\TabInterface $gramtab)
    {
        switch ($this->options['resolve_ancodes']) {
            case self::RESOLVE_ANCODES_AS_TEXT:
                return array(
                    '\Deimos\Morphy\AncodesResolver\ToText',
                    array($gramtab)
                );
            case self::RESOLVE_ANCODES_AS_INT:
                return array(
                    'phpMorphy_AncodesResolver_AsIs',
                    array()
                );
            case self::RESOLVE_ANCODES_AS_DIALING:
                return array(
                    'phpMorphy_AncodesResolver_ToDialingAncodes',
                    array(
                        $this->factory->open(
                            $this->options['storage'],
                            $this->bundle->getAncodesMapFile(),
                            true
                        ) // always lazy open
                    )
                );
            default:
                // todo
                throw new \Exception("Invalid resolve_ancodes option, valid values are RESOLVE_ANCODES_AS_DIALING, RESOLVE_ANCODES_AS_INT, RESOLVE_ANCODES_AS_TEXT");
        }
    }

    public function createGramInfo($storage)
    {
        //return new phpMorphy_GramInfo_RuntimeCaching(new phpMorphy_GramInfo_Proxy($storage));
        //return new phpMorphy_GramInfo_RuntimeCaching(phpMorphy_GramInfo::create($storage, false));

        $result = new Gram\Info\RuntimeCaching(
            new Gram\Info\Proxy\WithHeader(
                $storage,
                $this->bundle->getGramInfoHeaderCacheFile()
            )
        );

        if ($this->options['use_ancodes_cache']) {
            return new  Gram\Info\AncodeCache(
                $result,
                $this->factory->open(
                    $this->options['storage'],
                    $this->bundle->getGramInfoAncodesCacheFile(),
                    true
                ) // always lazy open
            );
        } else {
            return $result;
        }
    }

}