<?php

    /* Codeine
     * @author BreathLess
     * @description: GUID
     * @package Codeine
     * @version 8.x
     * @date 04.12.10
     * @time 14:56
     */

    setFn('Get', function ($Call)
    {
        $Output = '';

        for ($ic = 0; $ic < 64; $ic++)
            $Output.= dechex(rand(0,15));

        return $Output;
    });
