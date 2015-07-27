<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::loadOptions('Metric', null, $Call);
        $Data = [];

        foreach ($Call['Metrics'] as $MetricName => $MetricCall)
        {
            $LastDot = F::Run('Entity', 'Read', $Call,
            [
                'Entity'    => 'Metric',
                'One'       => true,
                'Where'     =>
                [
                    'Domain'    => $MetricCall['Domain'],
                    'Key'       => $MetricCall['Key']
                ],
                'Sort' =>
                [
                    'Created' => false
                ]
            ]);
            $Data[] = [$MetricName, '<number>'.$LastDot['Value'].'</number>', date(DATE_RFC2822, $LastDot['Created'])];
        }

        $Call['Output']['Content'][] =
            [
                'Type' => 'Table',
                'Value' => $Data
            ];

        return $Call;
    });

    setFn('Collect', function ($Call)
    {
        $Call = F::Apply('Metric.Collect', 'Do', $Call);
        $Call = F::Apply('System.Interface.HTTP', 'Redirect', $Call, ['Redirect' => '/control/Metric']);

        return $Call;
    });

    setFn('Plot', function ($Call)
    {
        $Call = F::Apply('Metric.Plot', 'Do', $Call);
        return $Call;
    });