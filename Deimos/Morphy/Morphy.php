<?php

namespace Deimos\Morphy;

use Deimos\Morphy\Fsa\Fsa;

class Morphy
{

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
        return new phpMorphy_Morphier_Helper(
            $gramInfo,
            $gramTab,
            $this->createAncodesResolver($gramTab, $this->bundle, true),
            $gramInfoAsText
        );
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