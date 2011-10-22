<?php

    /* Codeine
     * @author BreathLess
     * @description: man command wrapper
     * @package Codeine
     * @version 6.0
     * @date 21.11.10
     * @time 2:22
     */

    self::Fn('Exec', function ($Call)
    {
        if (!isset($Call['Value']))
            $Call['Value'] = 'man';
        
        return passthru('man '.$Call['Value']);
    });
