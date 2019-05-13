<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('beforeIOWrite', function ($Call)
    {
        if (F::Dot($Call, 'Storages.'.$Call['Storage'].'.Journal'))
            F::Log([
                F::Dot($Call, 'Storage'),
                F::Dot($Call, 'Where'),
                F::Dot($Call, 'Data'),
                F::Dot($Call, 'Reason')
            ], LOG_WARNING, 'Administrator');

        return $Call;
    });