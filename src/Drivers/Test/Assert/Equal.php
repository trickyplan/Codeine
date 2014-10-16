<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if ($Call['Return'] == $Call['Case']['Result']['Equal'])
            $Decision = true;
        else
        {
            $Call['Failure'] = true;
            $Decision = false;
        }

        return [$Decision, print_r($Call['Return'], true)];
    });