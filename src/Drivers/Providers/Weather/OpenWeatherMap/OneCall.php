<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 2020.x.x
     */

    setFn('Do', function ($Call)
    {
        $Weather = [];

        $Response = F::Run('IO', 'Read', $Call,
            [
                'Storage'   => 'Web',
                'Where'     =>
                    [
                        'ID'    => F::Dot($Call, 'Providers.Weather.OpenWeatherMap.OneCall.Endpoint')
                    ],
                'Data'      => [
                    'lat'   => F::Dot($Call, 'Latitude'),
                    'lon'   => F::Dot($Call, 'Longitude'),
                    'appid' => F::Dot($Call, 'Providers.Weather.OpenWeatherMap.OneCall.APIKey')
                ],
                'Behaviours'=>
                    [
                        'Cached'    =>
                            [
                                'Enabled'   => true,
                                'TTL'       => 3600,
                                'Keys'      => ['Storage', 'Where.ID', 'Data', 'IO One'],
                                'Result'    =>
                                    [
                                        'Allow Storing Empty' => false
                                    ]
                            ]
                    ],
                'Format' => 'Formats.JSON',
                'IO One'           => true
            ]);

        if (empty($Response))
        {
            $Weather = null;
            F::Log('Empty response from OpenWeatherMap', LOG_WARNING);
        }
        else
        {
            $Map = F::Dot($Call, 'Providers.Weather.OpenWeatherMap.OneCall.Map');
            $Weather['Now'] = [
                'Time' => $Response['current']['dt']
            ];

            foreach ($Map['Now'] as $From => $To)
                $Weather['Now'] = F::Dot($Weather['Now'], $To, F::Dot($Response['current'], $From));

            foreach ($Response['hourly'] as $Hourly)
            {
                $Time = date('Hi', $Hourly['dt']);
                $Weather['Hourly'][$Time] = ['Time' => $Time];
                foreach ($Map['Hourly'] as $From => $To)
                {
                    if ($Value = F::Dot($Hourly, $From))
                        $Weather['Hourly'][$Time] = F::Dot($Weather['Hourly'][$Time], $To, $Value);
                }
            }

            foreach ($Response['daily'] as $Daily)
            {
                $Date = date('Ymd', $Daily['dt']);
                $Weather['Daily'][$Date] = ['Date' => $Date];
                foreach ($Map['Daily'] as $From => $To)
                {
                    if ($Value = F::Dot($Daily, $From))
                        $Weather['Daily'][$Date] = F::Dot($Weather['Daily'][$Date], $To, $Value);
                }
            }
        }


        return $Weather;
    });
