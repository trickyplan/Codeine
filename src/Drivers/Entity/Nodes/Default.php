<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Write', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (!isset($Call['Data'][$Name]) or empty($Call['Data'][$Name]))
            {
                if (isset($Node['Default']))
                    $Call['Data'][$Name] = F::Live($Node['Default']); // FIXME Add flag
            }
        }
        return $Call;
    });

    self::setFn('Read', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (isset($Node['Default']))
            {
                $Node['Default'] = F::Live($Node['Default']);
                if (isset($Call['Data']))
                    foreach ($Call['Data'] as &$Element)
                        if (!isset($Element[$Name]) or empty($Element[$Name]))
                            $Element[$Name] = $Node['Default']; // FIXME Add flag
            }
        }

        return $Call;
    });