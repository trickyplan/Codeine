<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['Data']))
            $Call['Data'] = F::Diff($Call['Data'], $Call['Current']);


        return $Call;
    });