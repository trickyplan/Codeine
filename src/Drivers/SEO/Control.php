<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {

        return $Call;
    });

    setFn('Robots', function ($Call)
    {
        return F::Run('SEO.Robots', 'Do', $Call);
    });

    setFn('Sitemaps', function ($Call)
    {
        $Call['Output']['Content'][] = htmlentities(
            file_get_contents($Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/sitemap.xml'));

        return $Call;
    });