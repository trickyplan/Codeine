<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Output = '';

        foreach ($Call['Keys'] as $Key)
            $Output[] = $Call['Data'][$Key];

        return implode($Call['Glue'], $Output);
    });