<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

     setFn('Do', function ($Call)
    {
        $Sum = 0;

        foreach ($Call['Keys'] as $Key)
            $Sum += $Call['Data'][$Key];

        return ($Sum / (sizeof($Call['Keys'])));
    });