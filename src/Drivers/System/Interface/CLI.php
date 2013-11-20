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

        $Call = F::Hook('beforeInterfaceRun', $Call);

        $Call['Return Code'];
            if (!isset($Call['Skip Run']))
                $Call = F::Apply($Call['Service'], $Call['Method'], $Call);

        F::Run('IO','Write', $Call,
            [
                'Storage' => 'Output',
                'Where' => $Call['Service'].':'.$Call['Method'],
                'Data' => $Call['Output']
            ]);

        $Call = F::Hook('afterInterfaceRun', $Call);

        if (isset($Call['Failure']) && $Call['Failure'])
            $Call['Return Code'] = 1;

        return $Call;
    });