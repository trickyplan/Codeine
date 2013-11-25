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

        $Call = F::Apply(null, $Call['HTTP Method'], $Call);

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