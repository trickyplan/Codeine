<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Profile']['Summary']['Time'] = array_sum(self::$_Counters['T']);
        $Call['Profile']['Summary']['Calls'] = array_sum(self::$_Counters['C']);

        arsort(self::$_Counters['T']);

        $Stats =
        [
            'Memory: '.(memory_get_usage(true)/1024).'Kb ',
            'Peak memory: '.(memory_get_peak_usage(true)/1024).'Kb',
            'Internal storage: '.count(self::$_Storage)
        ];

        F::Run('IO', 'Write', $Call,
                [
                    'Storage' => 'Profiler',
                    'ID' => 'Profile: '.$Call['HTTP']['Host'].$Call['HTTP']['URL'],
                    'Data' => F::Run('Formats.Profile.'.$Call['Profile Format'], 'Do', $Call,
                        [
                            'Data' => self::$_Counters
                        ])
                ]);

        F::Run('IO', 'Write', $Call,
                [
                    'Storage' => 'Profiler',
                    'ID' => 'Profile: '.$Call['HTTP']['Host'].$Call['HTTP']['URL'],
                    'Data' => '<div class="alert alert-info">'.implode("\n", $Stats).'</div>'
                ]);

        return $Call;
    });