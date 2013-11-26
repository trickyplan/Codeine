<?php

    /*
     * @author BreathLess
     * @description Sitemap generator 
     * @package Winebase
     * @version 7.0
     */

    setFn('Generate', function ($Call)
    {
        $Elements = F::Run('Entity', 'Read', ['Entity' => 'Comment', 'Fields' => ['ID']]);
        $Data = [];

        foreach ($Elements as $Element)
            $Data[] = $Call['HTTP']['Host'].'/comment/'.$Element['ID']; // FIXME!

        return $Data;
     });