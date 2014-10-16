<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Result = '';

        Foreach ($Call['Keys'] as $Key)
            $Result.= F::Dot($Call, $Key);

        return $Result;
    });