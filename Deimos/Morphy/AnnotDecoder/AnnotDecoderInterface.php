<?php

namespace Deimos\Morphy\AnnotDecoder;

interface AnnotDecoderInterface
{
    function decode($annotsRaw, $withBase);
}