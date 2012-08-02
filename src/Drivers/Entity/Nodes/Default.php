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
            if (F::Dot($Call['Data'], $Name) === null)
            {
                if (isset($Node['Default']))
                    $Call['Data'] = F::Dot($Call['Data'], $Name, F::Live($Node['Default'])); // FIXME Add flag
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
                        if (F::Dot($Element, $Name) === null)
                            $Element = F::Dot($Element,$Name,$Node['Default']); // FIXME Add flag
            }
        }

        return $Call;
    });