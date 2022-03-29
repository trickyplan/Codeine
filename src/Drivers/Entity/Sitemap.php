<?php

    setFn('Generate.Sitemap.Handler', function ($Call) {
        $OverallEntities = F::Run('Entity', 'Count', $Call);

        if ($OverallEntities > 0) {
            $Chunks = ceil($OverallEntities / $Call['Sitemap']['Limits']['URLs Per Sitemap']);

            for ($IX = 0; $IX < $Chunks; $IX++) {
                $Call['Output']['Content'][] =
                    [
                        [
                            '_name' => 'sitemap',
                            '_children' =>
                                [
                                    [
                                        '_name' => 'loc',
                                        '_text' => $Call['Sitemap']['FQDN'] . '/sitemap/' . $Call['Handler'] . '/' . $IX . '.xml'
                                    ]
                                ]
                        ]
                    ];
            }
        }
        return $Call;
    });

    setFn('Generate.Sitemap', function ($Call) {
        $Objects = F::Run(
            'Entity',
            'Read',
            $Call,
            [
                'Skip Live' => true,
                'Fields' => $Call['Sitemap']['Field'],
                'Limit' =>
                    [
                        'From' => ($Call['Page']) * $Call['Sitemap']['Limits']['URLs Per Sitemap'],
                        'To' => ($Call['Page'] + 1) * $Call['Sitemap']['Limits']['URLs Per Sitemap']
                    ]
            ]
        );

        if (empty($Objects)) {
            ;
        } else {
            foreach ($Objects as $Object) {
                $Call['Output']['Content'][] =
                    [
                        'url' =>
                            [
                                'loc' => $Call['Sitemap']['FQDN'] . '/' . $Call['Sitemap']['Prefix'] . '/' . F::Dot(
                                        $Object,
                                        $Call['Sitemap']['Field']['URL']
                                    ),
                                'lastmod' => date(
                                    DATE_W3C,
                                    F::Dot($Object, $Call['Sitemap']['Field']['Last Modified'])
                                ),
                                'changefreq' => $Call['Sitemap']['Frequency'],
                                'priority' => $Call['Sitemap']['Priority']
                            ]
                    ];
            }
        }

        return $Call;
    });