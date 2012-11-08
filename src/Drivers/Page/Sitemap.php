<?php

    /* Sphinx
     * @author BreathLess
     * @description Sitemap generator 
     * @package Sphinx
     * @version 7.0
     */

    setFn('Generate', function ($Call)
    {
        $Elements = F::Run('Entity', 'Read', ['Entity' => 'Page', 'Fields' => ['Slug']]);
        $Data = [];

        foreach ($Elements as $Element)
            $Data[] = 'http://'.$Call['Host'].'/'.$Element['Slug']; // FIXME!

        return $Data;
     });