<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Apply(null, $Call['HTTP']['Method'], $Call);
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