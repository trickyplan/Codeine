<?php

    /* Codeine
     * @author bergstein@trickyplan.com
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
                    $Verbose = PHP_INT_MAX;
                break;

                case 'Orange':
                    $Verbose = LOG_DEBUG;
                break;

                case 'Yellow':
                    $Verbose = LOG_INFO;
                break;

                case 'Red':
                    $Verbose = LOG_NOTICE;
                break;

                case 'Black':
                    $Verbose = LOG_ERR;
                break;
            }

            F::Log('Latency level is *'.$Decision.'*, because total page time *'.$Total.'* ms', $Verbose, 'Performance');

            if ($Verbose <= LOG_NOTICE)
                self::$_Performance = true;

            $Call = F::Hook('Latency.Audit.'.$Decision, $Call);
        }

        return $Call;
    });