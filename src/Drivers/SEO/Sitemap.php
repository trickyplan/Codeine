<?php

    setFn('Prepare', function ($Call) {
        $Call['Sitemap']['FQDN'] = $Call['HTTP']['Proto'] . $Call['HTTP']['Domain']; // FIXME Add Port Support
        return $Call;
    });

    setFn('Generate.Sitemap.Root', function ($Call) {
        $Call = F::Apply(null, 'Prepare', $Call);

        $Handlers = F::Dot($Call, 'Sitemap.Handlers');
        $Sitemaps = [];

        foreach ($Handlers as $HandlerName => $HandlerConfiguration) {
            $Sitemaps[] =
                [
                    '_name' => 'sitemap',
                    '_children' =>
                        [
                            [
                                '_name' => 'loc',
                                '_text' => $Call['Sitemap']['FQDN'] . '/sitemap/' . $HandlerName . '.xml'
                            ]
                        ]
                ];
        }

        $Call['Output']['Content'] =
            [
                [
                    '_name' => 'sitemapindex',
                    '_children' => $Sitemaps
                ]
            ];

        return $Call;
    });

    setFn('Generate.Sitemap.Handler', function ($Call) {
        $Call = F::Apply(null, 'Prepare', $Call);

        $Call['Output'] = ['Root' => 'sitemapindex', 'Content' => []];

        if ($Handler = F::Dot($Call, 'Sitemap.Handlers.' . $Call['Handler'])) {
            $Call = F::Apply($Handler['Service'], null, $Call, $Handler['Call']);
        }

        return $Call;
    });

    setFn('Generate.Sitemap', function ($Call) {
        $Call = F::Apply(null, 'Prepare', $Call);

        $Call['Output'] = ['Root' => 'urlset', 'Content' => []];

        if ($Handler = F::Dot($Call, 'Sitemap.Handlers.' . $Call['Handler'])) {
            $Call = F::Apply($Handler['Service'], null, $Call, $Handler['Call']);
        }

        return $Call;
    });

    setFn('List.Sitemaps.RobotsTxt', function ($Call) {
        $Call = F::Apply(null, 'Prepare', $Call);
        $Sitemaps = [];

        $Handlers = F::Dot($Call, 'Sitemap.Handlers');
        foreach ($Handlers as $HandlerName => $HandlerConfiguration) {
            $Sitemaps[] = $Call['Sitemap']['FQDN'] . '/sitemap/' . $HandlerName . '.xml';
        }

        return $Sitemaps;
    });
