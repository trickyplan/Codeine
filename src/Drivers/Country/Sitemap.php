<?php

    /* Sphinx
     * @author BreathLess
     * @description Sitemap generator 
     * @package Sphinx
     * @version 7.0
     */

    setFn('Generate', function ($Call)
    {
        $Elements = F::Run('Entity', 'Read', ['Entity' => 'Country', 'Fields' => ['Slug']]);
        $Data = [];

        foreach ($Elements as $Element)
            $Data[] = $Call['Host'].'/country/'.$Element['Slug']; // FIXME!

        return $Data;
     });