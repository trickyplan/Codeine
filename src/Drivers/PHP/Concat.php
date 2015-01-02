<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Result = '';

        foreach ($Call['Keys'] as $Key)
            $Result.= F::Dot($Call, $Key);

        return $Result;
    });