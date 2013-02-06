<?php

    /* Sphinx
     * @author BreathLess
     * @description Sitemap generator 
     * @package Sphinx
     * @version 7.0
     */

    setFn('Generate', function ($Call)
    {
        $Elements = F::Run('Entity', 'Read', ['Entity' => 'Article', 'Fields' => ['Slug']]);
        $Data = [
            $Call['Host'].'/articles'
        ];

        foreach ($Elements as $Element)
            $Data[] = $Call['Host'].'/article/'.$Element['Slug']; // FIXME!

        return $Data;
     });