<?php

/* Codeine
 * @author bergstein@trickyplan.com
 * @description
 * @package Codeine
 * @version 2020.x.x
 */

    setFn('Do', function ($Call)
    {
        $Output = [];

        $Response = F::Run('IO', 'Read',
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
                        'Keys'      => ['Where.ID', 'Data', 'IO One']
                    ]
                ],
                'Format' => 'Formats.JSON',
                'IO One'           => true
            ]);

        $Map = F::Dot($Call, 'Providers.Weather.OpenWeatherMap.OneCall.Map');
        $Output['Now'] = [
            'Time' => $Response['current']['dt']
        ];

        foreach ($Map['Now'] as $From => $To)
            $Output['Now'] = F::Dot($Output['Now'], $To, F::Dot($Response['current'], $From));

        foreach ($Response['hourly'] as $Hourly)
        {
            $Time = date('Hi', $Hourly['dt']);
            $Output['Hourly'][$Time] = ['Time' => $Time];
            foreach ($Map['Hourly'] as $From => $To)
            {
                if ($Value = F::Dot($Hourly, $From))
                    $Output['Hourly'][$Time] = F::Dot($Output['Hourly'][$Time], $To, $Value);
            }
        }

        foreach ($Response['daily'] as $Daily)
        {
            $Date = date('Ymd', $Daily['dt']);
            $Output['Daily'][$Date] = ['Date' => $Date];
            foreach ($Map['Daily'] as $From => $To)
            {
                if ($Value = F::Dot($Daily, $From))
                    $Output['Daily'][$Date] = F::Dot($Output['Daily'][$Date], $To, $Value);
            }
        }

        return $Output;
    });
