<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Plot', function ($Call)
    {
        $Call = F::Apply('Metric.Plot', 'Do', $Call);
        return $Call;
    });