<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Match', function ($Call)
    {
        $Pockets = null;
        preg_match($Call['Pattern'], $Call['Value'], $Pockets);
        return $Pockets;
    });

    setFn('All', function ($Call)
    {
        $Pockets = null;
        preg_match($Call['Pattern'], $Call['Value'], $Pockets);
        return $Pockets;
    });

