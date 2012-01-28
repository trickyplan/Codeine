<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Run', function ($Call)
    {
        $Start = memory_get_usage();

            $Result = F::Run($Call['Value']);

        $Stop = memory_get_usage();

        F::Set('Memory:'.$Call['Value']['_N'],
            F::Run(array(
                '_N' => 'Science.Natural.Math.Statistics.Round',
                '_F' => 'Round',
                'Precision' => $Call['Precision'],
                'Value' =>
                    F::Run(array(
                            '_N' => 'Science.Natural.Math.Measure.Convert',
                            'Type' => 'Information',
                            'From' => 'B',
                            'To' => $Call['Unit'],
                            'Value' => $Stop - $Start
                        )))));

        return $Result;
    });

    self::setFn('afterProfile', function ($Call)
    {
        $Memory = F::Get();
        if (null !== $Memory)
            F::Run(
                array(
                    'Send' => 'Profiler',
                    'Message' => $Memory)
            );
    });