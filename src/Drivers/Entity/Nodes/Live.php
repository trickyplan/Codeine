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
            if (!isset($Call['Data'][$Name]) || empty($Call['Data'][$Name]))
            {
                if (isset($Node[$Call['Purpose']]) && F::isCall($Node[$Call['Purpose']]))
                    $Call['Data'][$Name] = F::Live($Node[$Call['Purpose']], $Call, array('Node' => $Name));
                else
                    if (isset($Node['Write']))
                        $Call['Data'][$Name] = F::Live($Node['Write'], $Call, array('Node' => $Name));
            }

        return $Call;
    });