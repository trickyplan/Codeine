<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Process', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (!isset($Node['Nullable']) && !$Node['Nullable'])
                if (!isset($Call['Data'][$Name])
                    || empty($Call['Data'][$Name])
                    || null == $Call['Data'][$Name]
                    || $Call['Data'][$Name] == $Call['Current'][$Name])
                    $Call['Data'][$Name] = $Call['Current'][$Name];
        }

        return $Call;
    });