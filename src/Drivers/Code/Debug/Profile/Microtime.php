<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Run', function ($Call)
    {
        $Start = microtime(true);
        $Result = F::Run($Call['Value']);
        $Stop = microtime(true);

        F::Set('Timer:'.$Call['Value']['_N'], round(($Stop - $Start)*$Call['Multiplier'], $Call['Precision']));

        return $Result;
    });

    self::setFn('afterProfile', function ($Call)
    {
        $Timers = F::Get();
        if (null !== $Timers)
            F::Run(
                array(
                    'Send' => 'Profiler',
                    'Message' => $Timers)
            );
    });