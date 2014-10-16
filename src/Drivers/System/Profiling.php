<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Start', function ($Call)
    {
        return F::Run($Call['Profiling'], 'Start', $Call);
    });

    setFn('Finish', function ($Call)
    {
        return F::Run($Call['Profiling'], 'Finish', $Call);
    });