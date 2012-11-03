<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Process', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (isset($Node['Hooks']))
                if(isset($Node['Hooks'][$Call['On']]))
                    $Call['Data'][$Name] = F::Live($Node['Hooks'][$Call['On']], $Call);
        }

        return $Call;
    });