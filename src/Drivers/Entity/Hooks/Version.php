<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Generate', function ($Call)
    {
        return $Call['Data']['Version']+1;
    });

    setFn('Where', function ($Call)
    {
        $Call['Sort']['Version'] = false;
        return $Call;
    });