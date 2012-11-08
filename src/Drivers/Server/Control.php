<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        return $Call;
    });

    setFn('Benchmark', function ($Call)
    {
        return F::Run('Server.Benchmark', 'Do', $Call);
    });