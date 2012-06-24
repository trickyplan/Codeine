<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

     self::setFn('Do', function ($Call)
    {
        $Sum = 0;

        foreach ($Call['Keys'] as $Key)
            $Sum += $Call['Data'][$Key];

        return ($Sum / (sizeof($Call['Keys'])));
    });