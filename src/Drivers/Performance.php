<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (self::$_Performance)
        {
            $Call['Performance']['Summary']['Time'] = array_sum(self::$_Counters['T']);
            $Call['Performance']['Summary']['Calls'] = array_sum(self::$_Counters['C']);

            arsort(self::$_Counters['T']);

            F::Log('Total time: '.round($Call['Performance']['Summary']['Time']).' ms', LOG_INFO, 'Performance');
            F::Log('Total calls: '.$Call['Performance']['Summary']['Calls'], LOG_INFO, 'Performance');
            F::Log('Total time per call: '.round($Call['Performance']['Summary']['Time'] / $Call['Performance']['Summary']['Calls'], 2).' ms'
                , LOG_INFO, 'Performance');

            F::Log('Memory: '.(memory_get_usage(true)/1024).'Kb ', LOG_INFO, 'Performance');
            F::Log('Peak memory: '.(memory_get_peak_usage(true)/1024).'Kb', LOG_INFO, 'Performance');

            foreach (self::$_Counters['T'] as $Key => $Value)
            {
                if (!isset(self::$_Counters['C'][$Key]))
                    self::$_Counters['C'][$Key] = 1;

                $Class =
                    [
                        'ATime' => LOG_INFO,
                        'RTime' => LOG_INFO,
                        'ACalls' => LOG_INFO,
                        'RCalls' => LOG_INFO,
                        'TimePerCall' => LOG_INFO
                    ];

                $Call['RTime'] = round(($Value / $Call['Performance']['Summary']['Time']) * 100, 2);
                $Call['RCalls'] = round((self::$_Counters['C'][$Key] / $Call['Performance']['Summary']['Calls']) * 100, 2);
                $Call['ATime'] = round($Value);
                $Call['ACalls'] = self::$_Counters['C'][$Key];
                $Call['TimePerCall'] = round($Value / self::$_Counters['C'][$Key], 2);

                if (isset($Call['Alerts']['Yellow']))
                    foreach ($Call['Alerts']['Yellow'] as $Metric => $Limit)
                        if ($Call[$Metric] > $Limit)
                            $Class[$Metric] = LOG_WARNING;

                if (isset($Call['Alerts']['Red']))
                    foreach ($Call['Alerts']['Red'] as $Metric => $Limit)
                        if ($Call[$Metric] > $Limit)
                            $Class[$Metric] = LOG_ERR;

                F::Log('*'.$Key.'* time is *'.$Call['ATime'].'* ms', $Class['ATime'], 'Performance');
                F::Log('*'.$Key.'* time is *'.$Call['RTime'].'%*', $Class['RTime'], 'Performance');
                F::Log('*'.$Key.'* calls is *'.$Call['ACalls'].'*', $Class['ACalls'], 'Performance');
                F::Log('*'.$Key.'* calls is *'.$Call['RCalls'].'%*', $Class['RCalls'], 'Performance');
                F::Log('*'.$Key.'* time per call is *'.$Call['TimePerCall'].'* ms', $Class['TimePerCall'], 'Performance');
            }
        }
        return $Call;
    });