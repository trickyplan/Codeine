<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn ('Do', function ($Call)
    {
        F::Log('CLI Interface Started', LOG_IMPORTANT);

        $Call = F::Hook('beforeInterfaceRun', $Call);

        $Call['HTTP']['IP'] = F::Live($Call['HTTP']['IP'], $Call);

        if (isset($Call['Project']['Hosts'][F::Environment()]))
            $Call['HTTP']['Host'] = $Call['Project']['Hosts'][F::Environment()];

        $Call['HTTP']['URL'] = '/';

            if (isset($Call['Skip Run']))
                F::Log('Run Skipped, because '.$Call['Skip Run']);
            else
                $Call = F::Apply($Call['Service'], $Call['Method'], $Call);

        if (is_array($Call) && isset($Call['Output']))
        {
            if (!isset($Call['View']['Renderer']))
                $Call['View']['Renderer'] = $Call['View']['Default']['Renderer'];

            $Call = F::Hook('afterInterfaceRun', $Call);

            F::Run('IO','Write', $Call,
                [
                    'Storage' => 'Output',
                    'Where' => $Call['Service'].':'.$Call['Method'],
                    'Data' => $Call['Output']
                ]);

            if (isset($Call['Failure']) && $Call['Failure'])
                $Call['Return Code'] = 1;
        }
/*
        else
            F::Run('IO','Write', $Call,
            [
                'Storage' => 'Output',
                'Data' => $Call
            ]);*/

        return $Call;
    });