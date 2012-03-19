<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Process', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (!isset($Call['Data'][$Name]))
            {
                if (isset($Node['Default']))
                    $Call['Data'][$Name] = F::Live($Node['Default']); // FIXME Add flag
                else
                    $Call['Data'][$Name] = null;
            }
        }
        return $Call;
    });