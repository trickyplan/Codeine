<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Metrics = F::loadOptions('Metric.Collect')['Metrics'];

        $Call['Layouts'][] = [
            'Scope' => 'Metric',
            'ID'    => 'Plot'
        ];

        $Call['Output']['Metric Selector'][] =
            [
                'Type'      => 'Form.Select',
                'Name'      => 'Metric',
                'Label'     => 'Metric.Plot:Select',
                'Value'     => isset($Call['Request']['Metric'])? $Call['Request']['Metric']: null,
                'Options'   => array_keys($Metrics),
                'Values as Keys' => true
            ];

        if (isset($Call['Request']['Metric']) && isset($Metrics[$Call['Request']['Metric']]))
        {
            $SelectedMetric = $Metrics[$Call['Request']['Metric']];

            $Data = F::Run('Metric.Aggregate', 'Do', $Call,
                [
                    'Where' =>
                    [
                        'Domain'    => F::Live($SelectedMetric['Domain'], $Call),
                        'Key'       => F::Live($SelectedMetric['Key'], $Call)
                    ],
                    'Limit' =>
                    [
                        'From'  => 0,
                        'To'    => 100
                    ],
                    'Sort'  =>
                    [
                        'Created' => false
                    ]
                ]);

            if (empty($Data))
                ;
            else
            {
                foreach ($Data as &$Row)
                    $Row[0] = strftime('%d.%m.%y/%T', $Row[0]);

                $Call = F::loadOptions('IO', null, $Call);

                $Hash = sha1(j($SelectedMetric).j($Data));

                $Call['Image URL'] = '/cache/img/'.$Hash.'.png';
                $Call['Image Filename'] =
                    $Call['Storages']['Image Cache']['Directory'].DS.$Call['HTTP']['Host'].DS.'img'.DS.$Hash.'.png';

                $Call['Data Filename'] =
                   $Call['Storages']['Image Cache']['Directory'].DS.$Call['HTTP']['Host'].DS.'img'.DS.$Hash.'.txt';

                    $Call['Plot']['Title'] = $Call['Request']['Metric'];
                    F::Run('Image.Plot.GNUPlot', 'Do', $Call,
                         [
                             'Data' => $Data
                         ]);
                }
            }

        return $Call;
    });