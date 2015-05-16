<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Start', function ($Call)
    {
        $Call['Progress']['Started'] = microtime(true);
        $Call['Progress']['Ticks'] = 0;
        $Call['Progress']['Ticks per log']  = round(($Call['Progress']['Max']-$Call['Progress']['Min']) / $Call['Progress']['Logs']);
        return $Call;
    });

    setFn('Log', function ($Call)
    {
        $Call['Progress']['Ticks']++;

        // if ($Call['Progress']['Ticks'] % $Call['Progress']['Ticks per log'] == 0)
        {
            $Call['Progress']['Percent'] = $Call['Progress']['Now']/($Call['Progress']['Max']-$Call['Progress']['Min']);
            $Call['Progress']['Elapsed'] = microtime(true) - $Call['Progress']['Started'];
            $Call['Progress']['Estimated'] = ((1/$Call['Progress']['Percent']) * $Call['Progress']['Elapsed']) - $Call['Progress']['Elapsed'];
            F::Log('Progress: '.(round($Call['Progress']['Percent'], $Call['Progress']['Precision'])*100).'% '.($Call['Progress']['Now'].'/'.($Call['Progress']['Max']-$Call['Progress']['Min'])), LOG_NOTICE, 'Developer');
            F::Log('Elapsed: '.round($Call['Progress']['Elapsed'], $Call['Progress']['Precision']).' sec.', LOG_NOTICE, 'Developer');
            F::Log('Estimated: '.round($Call['Progress']['Estimated'], $Call['Progress']['Precision']).' sec.', LOG_NOTICE, 'Developer');
        }

        return $Call;
    });

    setFn('Finish', function ($Call)
    {
        $Call['Progress']['Elapsed'] = microtime(true) - $Call['Progress']['Started'];
        F::Log('Elapsed: '.round($Call['Progress']['Elapsed'], $Call['Progress']['Precision']), LOG_NOTICE, 'Developer');
        unset($Call['Progress']);
        return $Call;
    });