<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 2019.x
     */

    setFn('beforeIOWrite', function ($Call)
    {
        if (F::Dot($Call, 'Storages.'.$Call['Storage'].'.ReadOnly'))
        {
            F::Log('ReadOnly flag is present for '.$Call['Storage'], LOG_INFO);
            $Call = F::Dot($Call, 'IO.Skip', true);
        }

        return $Call;
    });