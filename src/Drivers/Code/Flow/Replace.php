<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Run', function ($Call)
    {
        $Result = F::Live($Call['Run']);

        if ($Result === null)
            return F::Live($Call['Replace']);
        else
            return $Result;
    });