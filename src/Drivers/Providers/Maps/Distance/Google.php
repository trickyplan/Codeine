<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 2020.x.x
     */

    setFn('Get.Distance', function ($Call) {
        $Distance = null;
        $Result = F::Run(
            'IO',
            'Read',
            $Call,
            [
                'Storage' => 'Web',
                'Format' => 'Formats.JSON',
                'IO One' => true,
                'Where' =>
                    [
                        'ID' => $Call['Providers']['Maps']['Distance']['Google']['Endpoint']
                    ],
                'Data' =>
                    [
                        'key' => $Call['Providers']['Maps']['Distance']['Google']['APIKey'],
                        'units' => $Call['Providers']['Maps']['Distance']['Google']['Units'],
                        'origins' => $Call['From']['Latitude'] . ',' . $Call['From']['Longitude'],
                        'destinations' => $Call['To']['Latitude'] . ',' . $Call['To']['Longitude']
                    ],
                'Behaviours' =>
                    [
                        'Cached' =>
                            [
                                'Enabled' => true,
                                'TTL' => 86400 * 365,
                                'Keys' => ['Where.ID', 'Data', 'IO One']
                            ]
                    ]
            ]
        );

        if (isset($Result['rows'][0]['elements'][0]['distance']['value'])) {
            $Distance = $Result['rows'][0]['elements'][0]['distance']['value'];
        }

        return $Distance;
    });

    setFn('Get.Duration', function ($Call) {
        $Duration = null;
        $Result = F::Run(
            'IO',
            'Read',
            $Call,
            [
                'Storage' => 'Web',
                'Format' => 'Formats.JSON',
                'IO One' => true,
                'Where' =>
                    [
                        'ID' => $Call['Providers']['Maps']['Distance']['Google']['Endpoint']
                    ],
                'Data' =>
                    [
                        'key' => $Call['Providers']['Maps']['Distance']['Google']['APIKey'],
                        'units' => $Call['Providers']['Maps']['Distance']['Google']['Units'],
                        'origins' => $Call['From']['Latitude'] . ',' . $Call['From']['Longitude'],
                        'destinations' => $Call['To']['Latitude'] . ',' . $Call['To']['Longitude']
                    ],
                'Behaviours' =>
                    [
                        'Cached' =>
                            [
                                'Enabled' => true,
                                'TTL' => 86400 * 365,
                                'Keys' => ['Where.ID', 'Data', 'IO One']
                            ]
                    ]
            ]
        );

        if (isset($Result['rows'][0]['elements'][0]['duration']['value'])) {
            $Duration = $Result['rows'][0]['elements'][0]['duration']['value'];
        }

        return $Duration;
    });