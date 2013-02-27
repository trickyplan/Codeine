<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Get', function ($Call)
    {
        if (isset($Call['Value']['type']) && isset($Call['Map'] [$Call['Value']['type']]))
            return F::Live($Call['ID']). $Call['Map'] [$Call['Value']['type']];
        else
            return null;
    });