<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (self::$_Performance or F::Environment() === 'Development')
        {
            $Call['Performance']['Summary']['Time'] = round((microtime(true)-Started)*1000);
            $Call['Performance']['Summary']['Calls'] = array_sum(self::$_Counters['C']);
            $Call['Performance']['Summary']['Core Storage'] = count(self::$_Storage);

            arsort(self::$_Counters['T']);

            F::Log('Max stack size: '.F::Get('MSS'), LOG_NOTICE, 'Performance');
            F::Log('Total time: '.round($Call['Performance']['Summary']['Time']).' ms', LOG_NOTICE, 'Performance');
            F::Log('Total calls: '.$Call['Performance']['Summary']['Calls'], LOG_NOTICE, 'Performance');
            F::Log('Total time per call: '
                .round($Call['Performance']['Summary']['Time'] / $Call['Performance']['Summary']['Calls'], 2).' ms'
                , LOG_NOTICE, 'Performance');

            F::Log('Memory: *'.(memory_get_usage(true)/1024).'Kb* ', LOG_NOTICE, 'Performance');
            F::Log('Peak memory: *'.(memory_get_peak_usage(true)/1024).'Kb*', LOG_NOTICE, 'Performance');
            F::Log('Core Storage: ~*'.(round(mb_strlen(j(self::$_Storage))/1024)).'kb*', LOG_NOTICE, 'Performance');
            
            $ExcludedFromLimiting = F::Dot($Call, 'Performance.Excluded');
            foreach (self::$_Counters['T'] as $Key => $Value)
            {
                if (!isset(self::$_Counters['C'][$Key]))
                    self::$_Counters['C'][$Key] = 1;

                $Class =
                    [
                        'ATime' => LOG_DEBUG,
                        'RTime' => LOG_DEBUG,
                        'ACalls' => LOG_DEBUG,
                        'RCalls' => LOG_DEBUG,
                        'TimePerCall' => LOG_DEBUG
                    ];

                $Call['RTime'] = round(($Value / $Call['Performance']['Summary']['Time']) * 100, 2);
                $Call['RCalls'] = round((self::$_Counters['C'][$Key] / $Call['Performance']['Summary']['Calls']) * 100, 2);
                $Call['ATime'] = round($Value);
                $Call['ACalls'] = self::$_Counters['C'][$Key];
                $Call['TimePerCall'] = round($Value / self::$_Counters['C'][$Key], 2);

                $Yellow = F::Dot($Call, 'Performance.Limits.Yellow');
                
                if (in_array($Key, $ExcludedFromLimiting))
                    ;
                else
                {
                    if (empty($Yellow))
                        ;
                    else
                        foreach ($Yellow as $Metric => $Limit)
                            if ($Call[$Metric] > $Limit)
                                $Class[$Metric] = LOG_WARNING;
    
                    $Red = F::Dot($Call, 'Performance.Limits.Yellow');
                    
                    if (empty($Red))
                        ;
                    else
                        foreach ($Red as $Metric => $Limit)
                            if ($Call[$Metric] > $Limit)
                                $Class[$Metric] = LOG_ERR;
                }
                
                F::Log('*'.$Key.'* time is *'.$Call['ATime'].'* ms', $Class['ATime'], 'Performance');
                F::Log('*'.$Key.'* time is *'.$Call['RTime'].'%*', $Class['RTime'], 'Performance');
                F::Log('*'.$Key.'* calls is *'.$Call['ACalls'].'*', $Class['ACalls'], 'Performance');
                F::Log('*'.$Key.'* calls is *'.$Call['RCalls'].'%*', $Class['RCalls'], 'Performance');
                F::Log('*'.$Key.'* time per call is *'.$Call['TimePerCall'].'* ms', $Class['TimePerCall'], 'Performance');
            }
        }
        return $Call;
    });