<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('GetRates', function ($Call)
    {
        return F::Live($Call['Currencies'][$Call['From']][$Call['To']]['Rate']);
    });

    setFn('Convert', function ($Call)
    {
        return $Call['Value']*F::Live($Call['Currencies'][$Call['From']][$Call['To']]['Rate']);
    });