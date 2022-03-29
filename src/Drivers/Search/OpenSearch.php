<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call) {
        $XML =
            [
                '_name' => 'OpenSearchDescription',
                '_attributes' =>
                    [
                        'xmlns' => 'http://a9.com/-/spec/opensearch/1.1/'
                    ],
                '_children' =>
                    [
                        [
                            '_name' => 'ShortName',
                            '_text' => F::Dot($Call, 'Project.Title')
                        ],
                        [
                            '_name' => 'Description',
                            '_text' => F::Dot($Call, 'Project.Description.Full')
                        ],
                        [
                            '_name' => 'Language',
                            '_text' => F::Dot($Call, 'Locale')
                        ],
                        [
                            '_name' => 'InputEncoding',
                            '_text' => 'UTF-8'
                        ],
                        [
                            '_name' => 'OutputEncoding',
                            '_text' => 'UTF-8'
                        ],
                        [
                            '_name' => 'Url',
                            '_attributes' =>
                                [
                                    'type' => 'application/x-suggestions+json',
                                    'rel' => 'suggestions',
                                    'template' => $Call['HTTP']['FQDN'] . '/search.json?Query={searchTerms}'
                                ]
                        ],
                        [
                            '_name' => 'Url',
                            '_attributes' =>
                                [
                                    'type' => 'text/html',
                                    'rel' => 'results',
                                    'template' => $Call['HTTP']['FQDN'] . '/search?Query={searchTerms}'
                                ]
                        ],
                        [
                            '_name' => 'Url',
                            '_attributes' =>
                                [
                                    'type' => 'application/opensearchdescription+xml',
                                    'rel' => 'self',
                                    'template' => $Call['HTTP']['FQDN'] . '/opensearch.xml'
                                ]
                        ],
                        [
                            '_name' => 'Image',
                            '_attributes' =>
                                [
                                    'type' => 'image/vnd.microsoft.icon',
                                    'height' => 48,
                                    'width' => 48
                                ],
                            '_text' => $Call['HTTP']['FQDN'] . '/favicon.ico'
                        ],
                        [
                            '_name' => 'Attribution',
                            '_text' => F::Dot($Call, 'Project.Copyright.Holder')
                        ]
                    ]
            ];

        if ($EMail = F::Dot($Call, 'Project.OpenSearch.EMail')) {
            $XML['_children'][] =
                [
                    '_name' => 'Contact',
                    '_text' => $EMail
                ];
        }

        if ($Developer = F::Dot($Call, 'Project.OpenSearch.Developer')) {
            $XML['_children'][] =
                [
                    '_name' => 'Developer',
                    '_text' => $Developer
                ];
        }

        if ($SyndicationRight = F::Dot($Call, 'Project.OpenSearch.Syndication Right')) {
            $XML['_children'][] =
                [
                    '_name' => 'SyndicationRight',
                    '_text' => $SyndicationRight
                ];
        }

        if ($AdultContent = F::Dot($Call, 'Project.OpenSearch.Adult Content')) {
            $XML['_children'][] =
                [
                    '_name' => 'AdultContent',
                    '_text' => $AdultContent
                ];
        }

        $Call['HTTP']['Headers']['Content-Type:'] = 'application/opensearchdescription+xml';

        $Call['Output']['Content'][] = $XML;
        return $Call;
    });