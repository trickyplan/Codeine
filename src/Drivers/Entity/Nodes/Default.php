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
            if (!isset($Call['Data'][$Name]) or empty($Call['Data'][$Name]))
            {
                if (!isset($Node['Default']))
                    $Node['Default'] = null;

                $Call['Data'][$Name] = F::Live($Node['Default']); // FIXME Add flag
            }
        }
        return $Call;
    });