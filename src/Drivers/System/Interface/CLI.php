<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Do', function ($Call)
    {
        F::Log('CLI Interface Started', LOG_IMPORTANT);

        $Call['Locale'] = F::Live($Call['Locale'], $Call);

        $Call['HTTP']['Host'] = gethostname();
        $Call['HTTP']['URL'] = '/';

        $Call = F::Hook('beforeInterfaceRun', $Call);

                if (!isset($Call['Skip Run']))
                    $Call = F::Apply($Call['Service'], $Call['Method'], $Call);

        $Call = F::Hook('afterInterfaceRun', $Call);

        F::Run('IO','Write', $Call,
            [
                'Storage' => 'Output',
                'Where' => $Call['Service'].':'.$Call['Method'],
                'Data' => $Call['Output']
            ]);

        if (isset($Call['Failure']) && $Call['Failure'])
            $Call['Return Code'] = 1;

        return $Call;
    });