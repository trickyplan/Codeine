<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        F::Log('Start metric collecting', LOG_INFO);

        if (!isset($Call['Metrics']) or empty($Call['Metrics']))
            ;
        else
            foreach ($Call['Metrics'] as $MetricName => $MetricCall)
            {
                $Call['Dot']['Value']  = F::Live($MetricCall['Generator'], $Call);
                $Call['Dot']['Created']   = F::Live($Call['Timer'], $Call);
                $Call['Dot']['Domain'] = F::Live($MetricCall['Domain'], $Call);
                $Call['Dot']['Key']    = F::Live($MetricCall['Key'], $Call);

                $Last = F::Run('Entity', 'Read',
                    [
                        'Entity' => 'Metric',
                        'Where'  =>
                        [
                            'Domain' => $Call['Dot']['Domain'],
                            'Key'    => $Call['Dot']['Key']
                        ],
                        'Sort'   =>
                        [
                            'Created' => false
                        ],
                        'One'    => true
                    ]);

                // Check last dot
                if ($Last['Value'] == $Call['Dot']['Value'])
                {
                    F::Log('Value not changed, write skipping', LOG_INFO);
                    $Call = F::Hook('Metric.Stalled', $Call);
                }
                else
                {
                    F::Log('Value changed, writing', LOG_INFO);

                    $Call = F::Hook('beforeDotCreate', $Call);

                        F::Run('Entity', 'Create',
                            [
                                'Entity' => 'Metric',
                                'Data'   => $Call['Dot']
                            ]);

                    $Call = F::Hook('afterDotCreate', $Call);
                }
            }

        F::Log('Stop metric collecting', LOG_INFO);

        return $Call;
    });