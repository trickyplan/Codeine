<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Write', function ($Call)
    {
        return $Call['Value'];
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        return $Call['Value'];
    });

    setFn('Generate', function ($Call)
    {
        $Data = [];

        if ($Call['From'] > $Call['To'])
            for ($ix = $Call['From']; $ix>$Call['To'];$ix--)
                $Data[$ix] = $ix;
        else
            for ($ix = $Call['From']; $ix<$Call['To'];$ix++)
                $Data[$ix] = $ix;



        return $Data;
    });

    setFn('Populate', function ($Call)
    {
        return rand($Call['From'], $Call['To']);
    });