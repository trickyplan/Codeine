<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Apply(null, $Call['HTTP Method'], $Call);
        return $Call;
    });

    setFn('GET', function ($Call)
    {


        return $Call;
    });

    setFn('POST', function ($Call)
    {

        return $Call;
    });