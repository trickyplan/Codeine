<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn('Run', function ($Call)
    {
        $Start = microtime(true);
        $Result = F::Run($Call['Value']);
        $Stop = microtime(true);

        F::Set('Timer:'.$Call['ID'], round(($Stop - $Start)*$Call['Multiplier'], $Call['Precision']));

        return $Result;
    });

    self::Fn('afterProfile', function ($Call)
    {
        $Timers = F::Get();
        if (null !== $Timers)
            F::Run(
                array(
                    'Send' => 'Profiler',
                    'Message' => $Timers)
            );
    });