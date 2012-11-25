<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Run', function ($Call)
    {
        if (($Result = F::Live($Call['Run'])) === null)
            return null;
        else
            return $Result;
    });