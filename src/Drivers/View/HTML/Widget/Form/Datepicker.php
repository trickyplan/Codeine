<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
    {
        if (!isset($Call['Format']))
            $Call['Format'] = 'd.m.Y';

        if ($Call['Value'] == 0)
            $Call['Value'] = time();

        $Call['Value'] = date($Call['Format'], F::Live($Call['Value']));

        return $Call;
     });