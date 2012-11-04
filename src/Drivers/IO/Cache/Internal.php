<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('ID', function ($Call)
    {
        return 'IO.'.serialize([$Call['Storage'], $Call['Scope'],isset($Call['Where'])? $Call['Where']: '']);
    });

    setFn('Read', function ($Call)
    {
        if (($Result = F::Get(F::Run(null, 'ID', $Call))) !== null)
            $Call['Result'] = $Result;

        return $Call;
    });

    setFn('Write', function ($Call)
    {
        if ($Call['Data'] != F::Get(F::Run(null, 'ID', $Call)))
            F::Set(F::Run(null, 'ID', $Call), $Call['Data']);

        return $Call;
    });