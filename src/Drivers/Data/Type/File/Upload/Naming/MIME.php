<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Get', function ($Call)
    {
        return F::Live($Call['ID']). $Call['Map'] [$Call['Value']['type']];
    });