<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Layouts'][] =
        [
            'Scope' => 'SEO',
            'ID' => 'Stats'
        ];
        return $Call;
    });

    setFn('Robots', function ($Call)
    {
        return F::Run('SEO.Robots', 'Do', $Call);
    });

    setFn('Sitemaps', function ($Call)
    {
        $Call['Layouts'][] =
        [
            'Scope' => 'SEO',
            'ID' => 'Sitemaps'
        ];

        $Call['Output']['Content'][] = htmlentities(
            file_get_contents($Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/sitemap.xml'));

        return $Call;
    });