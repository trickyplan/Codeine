<?php

    /* Sphinx
     * @author bergstein@trickyplan.com
     * @description Sitemap generator 
     * @package Sphinx
     * @version 7.0
     */

    setFn('Generate', function ($Call)
    {
        $Elements = F::Run('Entity', 'Read', ['Entity' => 'Page', 'Fields' => ['Slug']]);
        $Data = [];

        if ($Elements !== null)
        foreach ($Elements as $Element)
            $Data[] = $Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/'.$Element['Slug']; // FIXME!

        return $Data;
     });