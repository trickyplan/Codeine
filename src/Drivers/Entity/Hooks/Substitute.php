<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Where', function ($Call)
    {
        if (isset($Call['Where']))
        {
            $Where = [];
            foreach ($Call['Where'] as $Key => $Value)
            {
                if (isset($Call['Nodes'][$Key]['Substitute']))
                    $Where[$Call['Nodes'][$Key]['Substitute']] = $Value;
                else
                    $Where[$Key] = $Value;
            }

            $Call['Where'] = $Where;
        }

        return $Call;
    });

    setFn('Read', function ($Call)
    {
        if (isset($Call['Data']))
        {
            $Data = [];

            foreach ($Call['Nodes'] as $Name => $Node)
            {
                if (isset($Node['Substitute']))
                {
                    foreach ($Call['Data'] as $ID => $Row)
                        if (isset($Call['Data'][$ID][$Node['Substitute']]))
                            $Data[$ID][$Name] = $Call['Data'][$ID][$Node['Substitute']];
                }
                else
                    foreach ($Call['Data'] as $ID => $Row)
                        if (isset($Call['Data'][$ID][$Name]))
                            $Data[$ID][$Name] = $Call['Data'][$ID][$Name];
            }

            $Call['Data'] = $Data;
        }



        return $Call;
    });

    setFn('Write', function ($Call)
    {
        $Call = F::Apply(null, 'Where', $Call);

        if (isset($Call['Data']))
        {
            $Data = [];

            foreach ($Call['Nodes'] as $Name => $Node)
            {
                if (isset($Node['Substitute']))
                {
                    if (isset($Call['Data'][$Name]))
                        $Data[$Node['Substitute']] = $Call['Data'][$Name];
                }
                else
                    if (isset($Call['Data'][$Name]))
                        $Data[$Name] = $Call['Data'][$Name];
            }

            $Call['Data'] = $Data;
        }

        return $Call;
    });