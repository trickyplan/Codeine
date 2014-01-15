<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Total = round(microtime(true)-Started, 4)*1000;

        $Decision = 'Green';

        foreach ($Call['Latency']['Audit']['Limits'] as $Limit => $Value)
            if ($Total >= $Value)
                $Decision = $Limit;

        switch($Decision)
        {
            case 'Green':
                $Verbose = LOG_GOOD;
            break;

            case 'Orange':
                $Verbose = LOG_INFO;
            break;

            case 'Yellow':
                $Verbose = LOG_WARNING;
            break;

            case 'Red':
                $Verbose = LOG_ERR;
            break;

            case 'Black':
                $Verbose = LOG_CRIT;
            break;
        }
        F::Log('Latency level is *'.$Decision.'*, because total page time *'.$Total.'* ms', $Verbose, 'Profiler');

        $Call = F::Hook('Latency.Audit.'.$Decision, $Call);

        return $Call;
    });