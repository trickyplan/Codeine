<?php

    setFn('Prepare', function ($Call)
    {
        $Call['Sitemap']['FQDN'] = $Call['HTTP']['Proto'].$Call['HTTP']['Domain']; // FIXME Add Port Support
        return $Call;
    });

    setFn('Generate.Sitemap.Root', function ($Call)
    {
        $Call = F::Apply(null, 'Prepare', $Call);
        $Call['Output'] =  ['Root' => 'sitemapindex', 'Content' => []];

        $Handlers = F::Dot($Call, 'Sitemap.Handlers');
        foreach ($Handlers as $HandlerName => $HandlerConfiguration)
            $Call['Output']['Content'][] =
                    [
                        'sitemap' =>
                            [
                                'loc' => $Call['Sitemap']['FQDN'].'/sitemap/'.$HandlerName.'.xml'
                            ]
                    ];

        return $Call;
    });

    setFn('Generate.Sitemap.Handler', function ($Call)
    {
        $Call = F::Apply(null, 'Prepare', $Call);

        $Call['Output'] =  ['Root' => 'sitemapindex', 'Content' => []];

        if ($Handler = F::Dot($Call, 'Sitemap.Handlers.'.$Call['Handler']))
            $Call = F::Apply($Handler['Service'], null, $Call, $Handler['Call']);

        return $Call;
    });

    setFn('Generate.Sitemap', function ($Call)
    {
        $Call = F::Apply(null, 'Prepare', $Call);

        $Call['Output'] =  ['Root' => 'urlset', 'Content' => []];

        if ($Handler = F::Dot($Call, 'Sitemap.Handlers.'.$Call['Handler']))
            $Call = F::Apply($Handler['Service'], null, $Call, $Handler['Call']);

        return $Call;
    });

    setFn('List.Sitemaps.RobotsTxt', function ($Call)
    {
        $Call = F::Apply(null, 'Prepare', $Call);
        $Sitemaps = [];

        $Handlers = F::Dot($Call, 'Sitemap.Handlers');
        foreach ($Handlers as $HandlerName => $HandlerConfiguration)
            $Sitemaps[] = $Call['Sitemap']['FQDN'].'/sitemap/'.$HandlerName.'.xml';

        return $Sitemaps;
    });