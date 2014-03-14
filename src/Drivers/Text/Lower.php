<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (is_string($Call['Value']))
            $Call['Value'] = mb_strtolower($Call['Value']);

        return $Call;
    });