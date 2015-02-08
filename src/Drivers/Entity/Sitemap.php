<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Sitemaps Count', function ($Call)
    {
        $Overall = F::Run('Entity', 'Count', $Call);

        return ceil($Overall / $Call['Sitemap']['Limits']['URLs Per Sitemap']);
    });

    setFn('Show Sitemap Index', function ($Call)
    {
        $SitemapsCount = F::Run(null, 'Sitemaps Count', $Call);

        $From = 1+($Call['Index Page']-1)*$Call['Sitemap']['Limits']['Sitemap Per Index'];
        $To = $Call['Index Page']*$Call['Sitemap']['Limits']['Sitemap Per Index'];

        if ($To > $SitemapsCount)
            $To = $SitemapsCount;

        for ($IX = $From; $IX <= $To; $IX++)
            $Call['Output']['Content'][] =
            [
                'sitemap' =>
                [
                    'loc' => $Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/sitemap/'.$Call['Entity'].'/'.$IX.'.xml'
                ]
            ];

        return $Call;
    });

    setFn('Sitemap', function ($Call)
    {
        if (is_array($Call['Sitemap']['URL Field']))
            ;
        else
            $Call['Sitemap']['URL Field'] = (array) $Call['Sitemap']['URL Field'];

        $Objects = F::Run('Entity', 'Read', $Call,
        [
            'Fields' => $Call['Sitemap']['URL Field'],
            'Limit' =>
            [
                'From' => ($Call['Page']-1)*$Call['Sitemap']['Limits']['URLs Per Sitemap'],
                'To'   => $Call['Sitemap']['Limits']['URLs Per Sitemap']
            ]
        ]);

        foreach ($Objects as $Object)
        {
            $Slug = [];

            foreach ($Call['Sitemap']['URL Field'] as $Slice)
                if (isset($Object[$Slice]))
                    $Slug[] = urlencode($Object[$Slice]);

            $Call['Output']['Content'][] =
                [
                    'url' =>
                        [
                            'loc' => $Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/'.$Call['Scope'].'/'.implode('/', $Slug),
                            'lastmod' => date(DATE_W3C),
                            'changefreq' => $Call['Frequency'],
                            'priority'   => $Call['Priority']
                        ]
                ];
        }

        return $Call;
    });