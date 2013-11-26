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

        F::Run('IO', 'Write', $Call,
                [
                    'Storage' => 'Profiler',
                    'ID' => 'Profile: '.$Call['HTTP']['Host'].$Call['HTTP']['URL'],
                    'Data' => F::Run('Formats.Profile.'.$Call['Profile Format'], 'Do', $Call,
                        [
                            'Data' => self::$_Counters
                        ])
                ]);

        return $Call;
    });