<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Audit', function ($Call)
    {
        if (isset($Call['Latency']['Audit']['Enabled']) && $Call['Latency']['Audit']['Enabled'])
        {
            $Total = round(microtime(true)-Started, 4)*1000;

            $Decision = 'Green';

            foreach ($Call['Latency']['Audit']['Limits'] as $Limit => $Value)
                if ($Total >= $Value)
                    $Decision = $Limit;

            $Verbose = LOG_DEBUG;

            switch($Decision)
            {
                case 'Green':
                    $Verbose = LOG_GOOD;
                break;

                case 'Orange':
                    $Verbose = LOG_NOTICE;
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

            F::Log('Latency level is *'.$Decision.'*, because total page time *'.$Total.'* ms', $Verbose, 'Performance');

            if ($Verbose <= LOG_NOTICE)
                self::$_Performance = true;

            $Call = F::Hook('Latency.Audit.'.$Decision, $Call);
        }

        return $Call;
    });