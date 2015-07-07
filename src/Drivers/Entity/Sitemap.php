<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Sitemaps Count', function ($Call)
    {
        $Overall = F::Run('Entity', 'Read', $Call,
            [
                'Sort' =>
                [
                    'ID' => false
                ],
                'Limit' =>
                [
                    'From'  => 0,
                    'To'    => 1
                ],
                'One'   => true
            ]);

        return ceil($Overall['ID'] / $Call['Sitemap']['Limits']['URLs Per Sitemap']);
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

        $From = ($Call['Page']-1)*$Call['Sitemap']['Limits']['URLs Per Sitemap'];

        $Objects = F::Run('Entity', 'Read', $Call,
        [
            'Fields' => $Call['Sitemap']['URL Field'],
            'Where'  =>
            [
                'ID' =>
                [
                    '$gt' => $From
                ]
            ],
            'Sort'   =>
            [
                'ID' => false
            ],
            'Limit'  =>
            [
                'From' => 0,
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

    setFn('Sitemaps.Generate', function ($Call)
    {
        $Amount = F::Run(null, 'Sitemaps Count', $Call);

        $Call = F::Apply('Code.Progress', 'Start', $Call);
        $Call['Progress']['Max'] = $Amount;

        for ($IX = 1; $IX <= $Amount; $IX++)
        {
            $VCall = F::Run('Entity.Sitemap', 'Sitemap', $Call,
            [
                'Entity' => $Call['Index'],
                'Page'   => $IX
            ]);

            $VCall = F::Run('View', 'Render', $VCall,
                [
                    'View' =>
                    [
                        'Renderer' =>
                        [
                            'Service'   => 'View.XML',
                            'Method'    => 'Render'
                        ]
                    ]
                ]
            );

            $Call['Progress']['Now'] = $IX;

            $Call = F::Apply('Code.Progress', 'Log', $Call);

            F::Run('IO', 'Write',
                [
                    'Storage'   => 'Static Sitemaps',
                    'Scope'     => $Call['Entity'],
                    'Where'     => $IX,
                    'Data'      => $VCall['Output']
                ]);
        }
        $Call = F::Apply('Code.Progress', 'Finish', $Call);

        return $Call;
    });