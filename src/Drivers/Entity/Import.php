<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeCreateDo', $Call);

        $Call = F::Apply(null, $Call['HTTP']['Method'], $Call);

        return $Call;
    });

    setFn('GET', function ($Call)
    {

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        F::Run('Entity','Create', $Call, ['Data' => json_decode($Call['Request']['Data'], true)]);
        return $Call;
    });

    setFn('Input', function ($Call)
    {
        F::Run('Entity', 'Delete', $Call);

        $Call['Data'] = json_decode(file_get_contents('php://stdin'), true);

        F::Log($Call['Entity'].' '.count($Call['Data']).' objects loaded from stdin', LOG_WARNING, 'Developer');

        foreach ($Call['Data'] as $Data)
            F::Run('Entity', 'Create', $Call, ['Skip Live' => true, 'Data!' => $Data]);

        return [];
    });