<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('beforeIOWrite', function ($Call)
    {
        if (F::Dot($Call, 'Storages.'.$Call['Storage'].'.Journal') == true)
            F::Log([
                F::Dot($Call, 'Storage'),
                F::Dot($Call, 'Where'),
                F::Dot($Call, 'Data'),
                F::Dot($Call, 'Reason')
            ], LOG_WARNING, 'Administrator');

        return $Call;
    });