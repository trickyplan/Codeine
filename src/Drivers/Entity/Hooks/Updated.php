<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['Current']))
            $Call['Data'] = F::Merge($Call['Current'], $Call['Data']);
        return $Call;
    });