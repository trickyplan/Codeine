<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Performance']['Summary']['Time'] = array_sum(self::$_Counters['T']);
        $Call['Performance']['Summary']['Calls'] = array_sum(self::$_Counters['C']);

        arsort(self::$_Counters['T']);

        $Stats =
        [
            'Memory: '.(memory_get_usage(true)/1024).'Kb ',
            'Peak memory: '.(memory_get_peak_usage(true)/1024).'Kb',
            'Internal storage: '.count(self::$_Storage)
        ];

        F::Log(
            F::Run('Formats.Performance.'.$Call['Performance Format'], 'Do', $Call,
                    [
                        'Data'  => self::$_Counters,
                        'Stats' => $Stats
                    ]), LOG_WARNING, 'Performance');

        return $Call;
    });